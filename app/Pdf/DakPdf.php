<?php

namespace App\Pdf;

use Codedge\Fpdf\Fpdf\Fpdf;

class DakPdf extends FPDF
{
    // Page header
    protected $dataHeader;
    function setHeader($data)
    {
        $this->dataHeader = $data;
    }
    function Header()
    {
        
        $data = $this->dataHeader;
        $this->Image('logo.png',6,4,15,15);
        $this->Ln(3);
        $this->SetLineWidth(0);
        $this->Line(4,22,205,22);
        $this->SetLineWidth(0);
        $this->Line(4,22.5,205,22.5);
        
        if(isset($data->semester)) {
            $this->SetLineWidth(0);
            $this->Line(4,55,205,55);
            $this->SetLineWidth(0);
            $this->Line(4,55.5,205,55.5);
        }
        
        $this->SetFont('Helvetica','B',12);
        $this->Cell(0,6,'UNIVERSITAS MUHAMMADIYAH',0,1,'C');
        $this->Cell(0,6,'METRO',0,1,'C');
        $this->SetFont('Helvetica','',9.5);
        $this->Cell(0,4,'Jl. Ki Hajar Dewantara No. 116 Kota Metro Telp.(0725)42445-42454 fax.(0725)42445,',0,1,'C');

        
        
        $smt     = (isset($data->semester)) ? $data->semester : [];
        $title   = (isset($data->title)) ? $data->title : 'DAFTAR ANGKA KREDIT';
        $matkul  = (isset($data->matkul)) ? $data->matkul : [];
        $prodi   = (isset($matkul->prodi)) ? $matkul->prodi : (object) ['nm_lemb' => '', 'jenjang_didik' => ''];
        $jurusan = (isset($prodi->jurusan)) ? $prodi->jurusan : (object) ['nama_jur	' => ''];
        $fakultas= (isset($jurusan->fakultas)) ? $jurusan->fakultas : (object) ['nama_fak' => ''];
        
        
        $mhs  = (isset($data->mahasiswa)) ? $data->mahasiswa : [];
        $dos  = (isset($data->pembimbing)) ? $data->pembimbing : [];
        
        $mhsnipd = (!empty($mhs)) ? $mhs->nipd : "";
        $mhsnama = (!empty($mhs)) ? $mhs->nm_pd : "";
        $dosnama = (!empty($dos)) ? $dos->nama_dosen: "";
        $mhsprod = (!empty($mhs)) ? $mhs->prodi->jenjang_didik . " ".$mhs->prodi->nm_lemb : "";
        $mhsfakl = (!empty($mhs)) ? $mhs->prodi->satuanpendidikan->sp_nama : "";
        
        
        $smtnama = (!empty($smt)) ? strtoupper($smt->nm_smt) : "";
        $this->Ln(5);
        $this->SetFont('Helvetica','B',9);
        $this->Cell(0,5,$title,0,1,'C');
        if(!empty($smtnama))
            $this->Cell(0,5,$smtnama,0,1,'C');

        $this->Ln(2);
        $this->SetFont('Helvetica','',8.5);
        $this->Cell(22,5,'Fakultas',0,0);
        $this->Cell(60,5,': '.$fakultas->nama_fak,0,0);
        $this->Cell(25,5,'Kode Matakuliah',0,0);
        $this->Cell(70,5,': '.$matkul->kode_mk,0,1);

        $this->Cell(22,5,'Jurusan',0,0);
        $this->Cell(60,5,': '.$jurusan->nama_jur,0,0);
        $this->Cell(25,5,'Mata Kuliah',0,0);
        $this->Cell(70,5,': '.$matkul->nm_mk,0,1);
        
        $this->Cell(22,5,'Program Studi',0,0);
        $this->Cell(60,5,': '.$prodi->jenjang_didik .' '. $prodi->nm_lemb,0,0);
        $this->Cell(25,5,'Kredit',0,0);
        $this->Cell(70,5,': '.$matkul->sks_mk	.' SKS',0,1);

        /* $this->Cell(28,5,'Jurusan',0,0); */
        /* $this->Cell(55,5,': Program Diploma III',0,1); */

        //$this->Cell(30,5,'Dosen Pembimbing',0,0);
        //$this->Cell(70,5,': '.$dosnama,0,0);
        /* $this->Cell(28,5,'Program Studi',0,0); */
        /* $this->Cell(55,5,': '.$mhsprod,0,1); */

        $this->Ln(4);
        if(!empty($data->colName)) :
            $colWidth = $data->colWidth;
            $i = 0;
            $this->SetFont('Helvetica','B',8.5);
            // if(isset($data->dok) && $data->dok == 'transkip') {
            //     $this->SetFont('Helvetica','B',9);
            // }
            foreach ($data->colName as $row) {
                if(isset($data->dok) && $data->dok == 'transkip') {
                    $lnTbl = ($row->nama == 'NO.') ? 'TL' : 'T';
                    $lnTbl = ($row->nama == 'AM') ? 'TR' : $lnTbl;
                    $this->Cell($colWidth[$i],6,$row->nama,$lnTbl);
                }
                else {
                    $this->Cell($colWidth[$i],7,$row->nama,0);
                }
                $i++;
            }
        endif;
        if(isset($data->dok) && $data->dok == 'transkip') {
            $this->Ln(6);
        }
        else {
            $this->Ln(10);
            $this->Line(4,65,205,65);
            $this->Line(4,65.5,205,65.5);
        }
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Courier','I',8);
        // Page number
        $this->Cell(30,10,'Tanggal '.date('d/m/Y'),0,0,'L');
        $this->Cell(0,10,'Halaman '.$this->PageNo().'/{nb}',0,0,'R');

    }
}