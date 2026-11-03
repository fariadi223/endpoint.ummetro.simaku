<?php

namespace App\Pdf;

use Codedge\Fpdf\Fpdf\Fpdf;

class KpuPdf extends FPDF
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
        $this->SetFont('Courier','',9);
        $this->Cell(0,4,'Jl. Ki Hajar Dewantara No. 116 Kota Metro Telp.(0725)42445-42454 fax.(0725)42445,',0,1,'C');
        
        $smt     = (isset($data->semester)) ? $data->semester : [];
        $title   = (isset($data->title)) ? $data->title : 'KARTU PESERTA UJIAN (KPU)';
        
        
        $mhs  = (isset($data->mahasiswa)) ? $data->mahasiswa : (object) [];
        $dos  = (isset($data->pembimbing)) ? $data->pembimbing : [];
        
        $mhsnipd = (!empty($mhs)) ? $mhs->nipd : "";
        $mhsnama = (!empty($mhs)) ? $mhs->nm_pd : "";
        $dosnama = (!empty($dos)) ? $dos->nm_sdm: "";
        $mhsprod = (!empty($mhs)) ? $mhs->prodi->jenjang_didik . " ".$mhs->prodi->nm_lemb : "";
        $mhsfakl = (!empty($mhs)) ? $mhs->prodi->jurusan->fakultas : (object) ['nama_fak' => ''];
        
        
        $smtnama = (!empty($smt)) ? strtoupper($smt->nm_smt) : "";
        $this->Ln(5);
        $this->SetFont('Courier','B',11);
        $this->Cell(0,5,$title,0,1,'C');
        if(!empty($smtnama))
            $this->Cell(0,5,$smtnama,0,1,'C');

        $this->Ln(2);
        $this->SetFont('Courier','',10);
        $this->Cell(30,5,'N P M',0,0);
        $this->Cell(60,5,': '. $mhsnipd,0,0);
        $this->Cell(25,5,'Fakultas',0,0);
        $this->Cell(70,5,': '.$mhsfakl->nama_fak,0,1);

        $this->Cell(30,5,'Nama Lengkap',0,0);
        $this->Cell(60,5,': '. $mhsnama,0,0);
        $this->Cell(25,5,'Program Studi',0,0);
        $this->Cell(70,5,': '.$mhsprod,0,1);
        
        $this->Cell(30,5,'Dosen PA./Wali',0,0);
        $this->Cell(60,5,': '.$dosnama,0,1);

        $this->Ln(4);
        if(!empty($data->colName)) :
            $colWidth = $data->colWidth;
            $i = 0;
            $this->SetFont('Courier','B',10);
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