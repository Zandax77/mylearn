<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\BankSoal;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class BankSoalController extends Controller
{
    public function index($mapel_id)
    {
        $mapel = Mapel::findOrFail($mapel_id);
        if ($mapel->guru_id !== auth()->id()) abort(403);
        
        $bankSoals = BankSoal::where('mapel_id', $mapel_id)->with('bab')->latest()->get();
        return view('guru.bank_soal.index', compact('mapel', 'bankSoals'));
    }

    public function template()
    {
        // Generate a DOCX template using PHPWord to allow rich formatting and image insertion.
        // The generated file will be streamed as a download named "template_bank_soal.docx".
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        $section->addText('Template Bank Soal', ['bold' => true, 'size' => 14]);
        $section->addText('Silakan isi tabel di bawah ini. Anda dapat menambahkan gambar pada kolom "Pertanyaan" dengan menempelkan gambar ke dalam dokumen.', ['size' => 10]);
        $section->addTextBreak(1);

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80]);
        
        // Header row (properly a single row with 5 cells)
        $table->addRow();
        $headerCells = ['Bab_ID (Opsional)', 'Tipe_Soal (pg/bs/jodoh)', 'Pertanyaan', 'Opsi_JSON', 'Jawaban_Benar_JSON'];
        foreach ($headerCells as $header) {
            $table->addCell(2000)->addText($header, ['bold' => true]);
        }
        
        // Example row (PG)
        $table->addRow();
        $table->addCell(2000)->addText(''); // Bab_ID optional
        $table->addCell(2000)->addText('pg');
        $table->addCell(4000)->addText('Ibukota Indonesia adalah?');
        $table->addCell(4000)->addText('["Jakarta","Bandung","Surabaya","Medan"]');
        $table->addCell(4000)->addText('["Jakarta"]');
        
        // Example row (BS)
        $table->addRow();
        $table->addCell(2000)->addText('');
        $table->addCell(2000)->addText('bs');
        $table->addCell(4000)->addText('Matahari terbit dari barat.');
        $table->addCell(4000)->addText('["Benar","Salah"]');
        $table->addCell(4000)->addText('["Salah"]');
        
        // Example row (Jodoh)
        $table->addRow();
        $table->addCell(2000)->addText('');
        $table->addCell(2000)->addText('jodoh');
        $table->addCell(4000)->addText('Jodohkan hewan berikut dengan jenisnya.');
        $table->addCell(4000)->addText('{"Kucing":"Mamalia","Elang":"Burung","Hiu":"Ikan"}');
        $table->addCell(4000)->addText('{"Kucing":"Mamalia","Elang":"Burung","Hiu":"Ikan"}');

        // Save to a temporary stream
        $tempFile = tempnam(sys_get_temp_dir(), 'banksoal') . '.docx';
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile, 'template_bank_soal.docx')->deleteFileAfterSend(true);
    }

    public function upload(Request $request, $mapel_id)
    {
        $mapel = Mapel::findOrFail($mapel_id);
        if ($mapel->guru_id !== auth()->id()) abort(403);

        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt,docx|max:4096'
        ]);

        $file = $request->file('file_csv');
        $extension = $file->getClientOriginalExtension();
        $created = 0;
        Log::info('BankSoal upload started', ['mapel_id' => $mapel_id, 'extension' => $extension]);
        if (strtolower($extension) === 'docx') {
            // Parse DOCX using PHPWord
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file->getRealPath());
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                        $rows = $element->getRows();
                        // Assume first row is header, skip it
                        foreach (array_slice($rows, 1) as $row) {
                            $cells = $row->getCells();
                            $values = [];
                            foreach ($cells as $cell) {
                                $values[] = $this->getCellText($cell);
                            }
                            if (count($values) < 5) continue;
                            
                            $bab_id = empty($values[0]) ? null : $values[0];
                            $tipe_soal = strtolower($values[1]);
                            $pertanyaan = $values[2];
                            
                            // Convert HTML entities like &quot; back to quotes
                            $opsi_raw = html_entity_decode($values[3]);
                            $jawaban_benar_raw = html_entity_decode($values[4]);
                            
                            $opsi = json_decode($opsi_raw, true);
                            $jawaban_benar = json_decode($jawaban_benar_raw, true);
                            
                            Log::info('Parsed row (DOCX)', [
                                'bab_id' => $bab_id,
                                'tipe_soal' => $tipe_soal,
                                'pertanyaan' => $pertanyaan,
                                'opsi_raw' => $values[3],
                                'opsi_decoded' => $opsi_raw,
                                'opsi' => $opsi,
                                'jawaban_benar_raw' => $values[4],
                                'jawaban_benar_decoded' => $jawaban_benar_raw,
                                'jawaban_benar' => $jawaban_benar,
                            ]);
                            
                            if (!in_array($tipe_soal, ['pg', 'bs', 'jodoh'])) {
                                Log::warning('Skipping row: invalid tipe_soal', ['tipe_soal' => $tipe_soal]);
                                continue;
                            }
                            
                            $soal = BankSoal::create([
                                'mapel_id' => $mapel_id,
                                'bab_id' => $bab_id,
                                'tipe_soal' => $tipe_soal,
                                'pertanyaan' => $pertanyaan,
                                'opsi' => $opsi,
                                'jawaban_benar' => $jawaban_benar,
                            ]);
                            $created++;
                            Log::info('BankSoal created (DOCX)', ['id' => $soal->id, 'mapel_id' => $mapel_id]);
                        }
                    }
                }
            }
        } else {
            // Existing CSV handling
            $handle = fopen($file->getRealPath(), 'r');
            // Skip header
            fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 5) continue;
                $bab_id = empty(trim($row[0])) ? null : $row[0];
                $tipe_soal = strtolower(trim($row[1]));
                $pertanyaan = trim($row[2]);
                
                $opsi_raw = html_entity_decode(trim($row[3]));
                $jawaban_benar_raw = html_entity_decode(trim($row[4]));
                
                $opsi = json_decode($opsi_raw, true);
                $jawaban_benar = json_decode($jawaban_benar_raw, true);
                
                Log::info('Parsed row (CSV)', [
                    'bab_id' => $bab_id,
                    'tipe_soal' => $tipe_soal,
                    'pertanyaan' => $pertanyaan,
                    'opsi' => $opsi,
                    'jawaban_benar' => $jawaban_benar,
                ]);
                
                if (!in_array($tipe_soal, ['pg', 'bs', 'jodoh'])) {
                    Log::warning('Skipping row: invalid tipe_soal', ['tipe_soal' => $tipe_soal]);
                    continue;
                }
                
                $soal = BankSoal::create([
                    'mapel_id' => $mapel_id,
                    'bab_id' => $bab_id,
                    'tipe_soal' => $tipe_soal,
                    'pertanyaan' => $pertanyaan,
                    'opsi' => $opsi,
                    'jawaban_benar' => $jawaban_benar,
                ]);
                $created++;
                Log::info('BankSoal created (CSV)', ['id' => $soal->id, 'mapel_id' => $mapel_id]);
            }
            fclose($handle);
        }
        
        Log::info('BankSoal upload completed', ['created' => $created, 'mapel_id' => $mapel_id]);
        
        return redirect()->route('guru.bank_soal.index', $mapel_id)
            ->with('success', "Soal berhasil diunggah ke Bank Soal. ({$created} soal ditambahkan)");
    }

    private function getCellText($cell)
    {
        $text = '';
        foreach ($cell->getElements() as $element) {
            $text .= $this->getElementText($element);
        }
        return trim($text);
    }

    private function getElementText($element)
    {
        $text = '';
        if ($element instanceof \PhpOffice\PhpWord\Element\Text) {
            $text .= $element->getText();
        } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
            foreach ($element->getElements() as $subElement) {
                $text .= $this->getElementText($subElement);
            }
        } elseif ($element instanceof \PhpOffice\PhpWord\Element\Cell) {
            foreach ($element->getElements() as $subElement) {
                $text .= $this->getElementText($subElement);
            }
        } elseif ($element instanceof \PhpOffice\PhpWord\Element\Image) {
            try {
                $imgData = $element->getImageString();
                if ($imgData) {
                    $ext = $element->getImageExtension() ?: 'png';
                    $filename = uniqid('soal_') . '.' . $ext;
                    $path = 'uploads/soal_images/' . $filename;
                    
                    $binary = base64_decode($imgData, true);
                    if ($binary === false || base64_encode($binary) !== $imgData) {
                        $binary = $imgData;
                    }
                    
                    \Illuminate\Support\Facades\Storage::disk('public')->put($path, $binary);
                    $imageUrl = asset('storage/' . $path);
                    $text .= '<br><img src="' . $imageUrl . '" class="max-w-xs my-2 rounded border" alt="Gambar">';
                }
            } catch (\Throwable $e) {
                Log::error('Error extracting image from DOCX cell', ['message' => $e->getMessage()]);
            }
        }
        return $text;
    }

    public function destroy($id)
    {
        $soal = BankSoal::findOrFail($id);
        if ($soal->mapel->guru_id !== auth()->id()) abort(403);
        
        $mapel_id = $soal->mapel_id;
        $soal->delete();
        
        return redirect()->route('guru.bank_soal.index', $mapel_id)->with('success', 'Soal berhasil dihapus dari Bank Soal.');
    }
}
