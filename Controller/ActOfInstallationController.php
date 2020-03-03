<?php


class ActOfInstallationController
{
    /**
     * Generate and encode act of installation in the pdf format.
     *
     * @param string $date
     * @param string $responsible_person
     * @param string $head
     * @param array $members
     * @param $items
     * @see MPDFGenerator::actOfInstallation()
     */
    public static function generateActOfInstallation(string $date, string $responsible_person, string $head, array $members, $items): void {
        $mpdf = MPDFGenerator::getInstance();
        echo base64_encode($mpdf -> actOfInstallation($date, $responsible_person, $head, $members, $items));
    }
}