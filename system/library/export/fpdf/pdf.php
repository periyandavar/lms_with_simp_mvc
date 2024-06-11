<?php
/**
 * Pdf
 * php version 7.3.5
 *
 * @category Export
 * @package  Export
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
require_once 'fpdf.php';
defined('VALID_REQ') or exit('Invalid request');
/**
 * Pdf Class extends fpf set header and footer
 *
 * @category Export
 * @package  Export
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class Pdf extends FPDF
{
    /**
     * Sets pdf Header
     *
     * @return void
     */
    public function header()
    {
        $this->Image('static/img/favicon.png', 30, 8, 20);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(80);
        $this->Cell(80, 10, 'LMS', 1, 0, 'C');
        $this->Ln(20);
    }

    /**
     * Sets pdf Footer
     *
     * @return void
     */
    public function footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}
