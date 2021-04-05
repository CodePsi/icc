<?php

namespace Icc\PdfGenerator;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Icc\Utils\Utils;

class MPDFGenerator {
    private static $mpdfGeneratorInstance = null;


    /**
     * Singleton pattern implementation for the MPDFGenerator class
     *
     * @return MPDFGenerator
     */
    public static function getInstance(): MPDFGenerator
    {
        if (!isset(self::$mpdfGeneratorInstance)) {
            self::$mpdfGeneratorInstance = new self();
        }

        return self::$mpdfGeneratorInstance;
    }

    private function __construct()
    {
    }

    protected function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * This function generates the pdf representation of the passed request.
     * The problem is there's no support of many css features in the MPDF library, so
     * for solving basic things there is more appropriate to use html table and some css stuff for this.
     * It can be messed up, but there is no adequate way to do it, more messed up variant
     * you can look above. Because of this above class is deprecated, so do not use it.
     * It is working (above code and class), but it is the unstable code and it may be break up at any time.
     * I know it looks like spaghetti code, but who cares about this stuff? And also
     * there is no way to do it in another way, as I said above, it is messed up or can be.
     * The "@page" thing in the css style is options for the whole pdf document.
     *
     * @param $numberId
     * @param $date
     * @param $time
     * @param $responsiblePerson
     * @param $position
     * @param $phone
     * @param $building
     * @param $auditorium
     * @param $inventoryNumbers
     * @param $task
     * @param $technicalTicketNeeded
     * @return string document information for further encoding (mostly it is used for passing data between client and server and just encoding retrieved information)
     */
    public function generateRequest($numberId, $date, $time, $responsiblePerson, $position, $phone, $building, $auditorium, $inventoryNumbers, $task, $technicalTicketNeeded): string
    {
        try {
            $mpdf = new \Mpdf\Mpdf([

            ]);
            $inventory_number = '';
            $technicalTicketContent = '';
            $technicalTicketStyles = '';

            for ($i = 0; $i < count($inventoryNumbers); $i++) {
                if ($i == count($inventoryNumbers) - 1)
                    $inventory_number .= $inventoryNumbers[$i][2];
                else
                    $inventory_number .= $inventoryNumbers[$i][2] . ', ';
            }
            if (empty($inventory_number)) {
                $inventory_number = '________________';
            }
            if ($technicalTicketNeeded) {
                $technicalTicketData = $this->getTechnicalTicket($date, $numberId, $building, $auditorium, $inventory_number);
                $technicalTicketContent = $technicalTicketData['content'];
                $technicalTicketStyles = $technicalTicketData['style'];
            }
            $mpdf -> WriteHTML('
            <style>
                @page {
                    margin-top: 0.5cm;
                }
                body {
                    margin: 0;
                    padding: 0;
                    font-family: "Times New Roman", serif;
                }
                .header {
                    text-align: center;
                    font-weight: bold;
                }
                .main_information p {
                    margin: 5px 0 5px 0;
                }
                .task_table {
                    border: 1px solid black;                
                }
                .task_table td {
                    padding: 4px 0 4px 0;
                    width: 700px;
                }
                .standard_table{
                    border-spacing: 0;
                }
                .standard_table td{
                    border-spacing: 0;
                }
                .standard_table tr{
                    border-spacing: 0;
                }
                
                .standard_table tbody{
                    border-spacing: 0;
                }
                
                .reasons_table td{
                }
                ' . $technicalTicketStyles . '
            </style>
            <body>
                <header class="header">
                    Заявка на виконання робіт / обслуговування комп`ютерної техніки №' . strval($numberId) . '       
                </header>
                <div class="main_information">
                    <p><b>Дата: </b>'. $date .'<b> Час: </b>' . $time . '</p>
                    <p><b>Відповідальна особа: </b>' . $responsiblePerson . ', ' . $position . '</p>
                    <p><b>Контактний телефон: </b>'. $phone .'</p>
                    <p><b>Корпус: </b>' . $building . '<b> Аудиторія: </b>' . $auditorium . '</p>
                    <p><b>Інв. номер техніки: </b>'. $inventory_number .'</p>
                    <p><b>Завдання / ознаки несправності КТ: </b></p>
                    <table class="task_table"><tr><td>' . $task . '</td></tr></table>
                    <table class="standard_table"><tr><td style="width: 500px"><b>Огляд (ремонт): негайно/ _______________ о _______________</b></td><td><b>Шестопалова О. В.</b></td></tr></table>
                    <p><b>Результати роботи огляду інженером ІОЦ: </b></p>
                    <table class="standard_table"><tr><td>Наявність контрольного талону _____________________ </td><td style="">Операційна система _____________________</td></tr></table>
                    <p>_______________________________________________________________________________________________________</p>
                    <p>_______________________________________________________________________________________________________</p>
                    <p>_______________________________________________________________________________________________________</p>
                    <table class="standard_table reasons_table"><tr><td style="width: 150px;">Відремонтовано</td><td style="width: 220px;">Причина не встановлена</td><td style="width: 230px;">Тимчасове відновлення</td><td>Повторно</td></tr></table>
                    <p>Техніку взято для подальшої роботи в ІОЦ □</p>
                    <p>Виконавець – інженер ІОЦ __________________________________________________________________________</p>
                    <p style="margin-left: 400px; font-size: 9px"><span style="">(ПІБ, підпис)</span></p>
                    <p><b>Відповідальна особа:</b>  _____________________________________________________________________________</p>
                    <p style="margin-left: 400px; font-size: 9px"><span style="">(ПІБ, підпис)</span></p>
                </div>' . $technicalTicketContent . '
            </body>
            ');
            return $mpdf -> Output("doc.pdf", "S");
        } catch (MpdfException $e) {
        }
        return "ERROR";
    }

    public function getTechnicalTicket($date, $numberOfTicket, $building, $auditorium, $inventoryNumber): array {
        return ['style' => '<style>
                 @page {
                    margin-top: 0.5cm;
                }
                .main_information p {
                    margin: 5px 0 5px 0;
                }   
                .standard_table{
                    border-spacing: 0;
                }
                .standard_table td{
                    border-spacing: 0;
                }
                .standard_table tr{
                    border-spacing: 0;
                }
                
                .standard_table tbody{
                    border-spacing: 0;
                }       
            </style>',
            'content' => '
                
                <div style="margin-top: 50px" class="main_information">
                <table class="standard_table"><tr><td style="width: 280px"><b>Дата: </b>' . $date . '</td><td><b>Талон на технічне обслуговування №' . $numberOfTicket . '</b></td></tr></table>
                <p><b>Корпус</b> ' . $building . ' <b>, аудиторія</b> ' . $auditorium . ',   <b>Інв. номер комп’ютера</b> ' . $inventoryNumber . '</p>
                <p><b>Наявність гарантії: нема/до</b> ____________</p>
                <p>Зауваження (фахівець ІОЦ)</p>
                <p>_______________________________________________________________________________________________________</p>
                <p>_______________________________________________________________________________________________________</p>
                <p>_______________________________________________________________________________________________________</p>
                <p>Фахівець ІОЦ _________________________________ </p>
                <p><b>Відповідальна особа</b> ____________________ <b>дата</b> ____________________</p>
                <p>_______________________________________________________________________________________________________</p>
                <p>_______________________________________________________________________________________________________</p>
                </div>
            '];
    }

    public function generateTechnicalTicket($date, $numberOfTicket, $building, $auditorium, $inventoryNumber) {
        try {
//            $mpdf = new \Mpdf\Mpdf([]);
//            $mpdf -> WriteHTML();
//            $mpdf -> Output();
        } catch (MpdfException $e) {
        }
    }

    /**
     * @param $date string Pattern "Month (In ukrainian) year"
     * @param $responsible_person string
     * @param $head string
     * @param array $members string array
     * @param $items array
     * @return string|null
     */
    public function actOfInstallation(string $date, array $responsiblePerson, string $head, array $members, array $items): ?string
    {
        try {
            $mpdf = new \Mpdf\Mpdf();
            $html_suitable_items = "";
            $index_number = 0;
            if (count($members) === 0) {
                array_push($members, '');
                array_push($members, '');
                array_push($members, '');
            }
            foreach ($items as $item) {
                $html_suitable_items .= '<tr>';
                $html_suitable_items .= '<td>' . ++$index_number . '</td>';
                $html_suitable_items .= '<td>' . $item[0] . '</td>';
                $html_suitable_items .= '<td>' . $item[1] . '</td>';
                $html_suitable_items .= '<td>' . $item[2] . '</td>';
                $html_suitable_items .= '<td>' . $item[3] . '</td>';
                $html_suitable_items .= '<td>' . $item[4] . '</td>';
                $html_suitable_items .= '<td>' . $item[5] . '</td>';
                $html_suitable_items .= '</tr>';
            }
            $mpdf->WriteHTML('
    <style>
        .pre-header p {
    
            margin: 0 0 0 410px;
            font-size: 15px;
            font-family: "Times New Roman",serif;
        }
        .header{
    //        margin: 3px 0 0 0;
        }
        .center_head p {
            text-align: center;
            margin: 0 0 0 0;
        }
        .header_info {
            display: flex;
            color: red;
        }
        .header_table td {
            width: 300px;
        }
        .footer_table {
            margin: 10px 0 0 0;
        }
        .footer_table td {
            width: 300px;
        }
        .items_table {
            border-collapse: collapse;
            border: 1px solid darkgrey;
        }
        .items_table th{
            font-family: "Times New Roman",serif;
            font-weight: normal;
            border: 1px solid darkgrey;
        }
        .items_table tr{
            border: 1px solid darkgrey;
        }
        .items_table td{
            font-family: "Times New Roman",serif;
            padding: 6px 6px 5px 6px;
            border: 1px solid darkgrey;
        }
    </style>
    
    <div class="pre-header"><p>ЗАТВЕРДЖУЮ</p><p>Ректор Я. В.Шрамко</p><p>«___» ____________ 20__ р.</p></div>
    <div class="header"><div class="center_head"><p>АКТ УСТАНОВКИ</p><p> За' . $date . '</p></div>
    <table class="header_table">
        <tr>
            <td>Підрозділ:</td>
            <td>ІОЦ</td>
        </tr>
        <tr>
            <td>Матеріально відповідальна особа:</td>
            <td>' . $responsiblePerson[0] . '</td>
        </tr>
        <tr>
            <td>Комісія у складі:</td>
            <td></td>
        </tr>
        <tr>
            <td>Голова комісії:</td>
            <td>' . $head . '</td>
        </tr>
        <tr>
            <td>Члени комісії:</td>
            <td>1. ' . $members[0][1] . '</td>
        </tr>
        <tr>
            <td></td>
            <td>2. ' . $members[1][1] . '</td>
        </tr>
        <tr>
            <td></td>
            <td>3. ' . $members[2][1] . '</td>
        </tr>
    </table>
    <table class="items_table">
        <tr>
            <th>№п/п</th>
            <th>Найменування та опис(марка, ґатунок і т. д.)</th>
            <th>Одиниця виміру</th>
            <th>Кількість</th>
            <th>Ціна за одиницю</th>
            <th>Сума</th>
            <th>Місце встановлення</th>
        </tr>
        ' . $html_suitable_items . '
    </table>
    <table class="footer_table">
        <tr>
            <td>Голова комісії</td>
            <td>' . $head . '</td>
        </tr>
    
        <tr>
            <td>Члени комісії:</td>
            <td>1. ' . $members[0][1] . '</td>
        </tr>
        <tr>
            <td></td>
            <td>2. ' . $members[1][1] . '</td>
        </tr>
        <tr>
            <td></td>
            <td>3. ' . $members[2][1] . '</td>
        </tr>
        <tr>
        <td>Матеріально відповідальна особа</td>
        <td>' . $responsiblePerson[0] . '   </td>
    </tr>
    </table>
    </div>
    
    ');
            return $mpdf -> Output("doc.pdf", "S");
        } catch (MpdfException $e) {
        }
        return null;
    }

    /**
     * @param $items array
     * @param $members array Pattern: ["Member (example: Загородько П. В.)":"Position", ...]
     * @param $date array Pattern: ["year", "day", "month's name (In Ukrainian)"]
     * @return string|null
     */
    public function writeOffAct(array $items, array $members, array $date): string {
        $html_suitable_items = "";
        $index_number = 0;
        $total_amount = 0;
        $total_price = 0;
        foreach ($items as $item) {
            $html_suitable_items .= '<tr>';
            $html_suitable_items .= '<td>' . ++$index_number . '.</td>';

            //TODO Replace with cycle
            $html_suitable_items .= '<td>' . $item[0] . '</td>';
            $html_suitable_items .= '<td>' . $item[1] . '</td>';
            $html_suitable_items .= '<td>' . $item[2] . '</td>';
            $html_suitable_items .= '<td>' . $item[3] . '</td>';
            $html_suitable_items .= '<td>' . $item[4] . '</td>';
            $html_suitable_items .= '<td>' . $item[5] . '</td>';

            $html_suitable_items .= '</tr>';
            $total_amount += intval($item[2]);
            $total_price += $item[4];
        }
        if (strpos(strval($total_price), '.') !== false)
            $frac_part = explode('.', $total_price)[1];
        if (empty($frac_part)) $frac_part = 0;
        if ($total_price % 10 == 1) $grn_corr_name = ' гривня';
        else if ($total_price % 10 > 4 || $total_price % 10 == 0 ) $grn_corr_name = ' гривень';
        else $grn_corr_name = ' гривені';
        if ($frac_part % 10 == 1) $k_corr_name = ' копійка';
        else if ($frac_part % 10 > 4 || $frac_part % 10 == 0 ) $k_corr_name = ' копійок';
        else $k_corr_name = ' копійки';
        $html_suitable_members = '';
        $startValue = 'Члени комісії';
        $listMembers = '';
        for ($i = 3; $i < count($members); $i++) {
            if ($i > 3) {
                $startValue = '';
            }
            if ($i === count($members) - 1) {
                $listMembers .= $members[$i][4] . ' ' . $members[$i][1];
            } else {
                $listMembers .= $members[$i][4] . ' ' . $members[$i][1] . ', ';
            }
            $html_suitable_members .= '<tr style="border-bottom: 1px solid white">
            <td> ' . $startValue . '</td>
            <td style="border: 1px solid white">' . $members[$i][4] . '</td> <td style="border: 1px solid white">_________________</td> <td style="border: 1px solid white;">' . $members[$i][1] . '</td> 
        </tr>';
        }
        try {

            $mpdf = new Mpdf();
            $mpdf -> WriteHTML('
            <style>
                @page {
                    margin-top: 0.5cm;
                    margin-left: 1cm;;
                }
                .header_table {
                    width: 300px;
                }
                
                .no_spacing {
                    width: 10px;
                    margin: 0 0 0 0;
                }
        .items_table {
            border-collapse: collapse;
            border: 1px solid darkgrey;
        }
        .items_table th{
            font-family: "Times New Roman",serif;
            font-weight: normal;
            border: 1px solid darkgrey;
        }
        .items_table tr{
            border: 1px solid darkgrey;
        }
        .items_table td{
            font-family: "Times New Roman",serif;
            padding: 6px 6px 5px 6px;
            border: 1px solid darkgrey;
        }
        .no_borders {
            border-collapse: collapse;
            border: 1px solid white;
        }
        .no_borders td {
                    border-collapse: collapse;

            border: 1px solid white;
        }
        .no_borders tr{
            border-collapse: collapse;
            border: 1px solid white;
        }
        .no_borders th{
            border-collapse: collapse;
            border: 1px solid white;
        }
            </style>
            <p style="margin-left: 500px; font-weight: bold;">Типова форма N З-2</p>
            <div><div><div style="width: 370px; float: left;"><div style="float: left; margin-left: 40px;">_________ КДПУ________</div><div style="margin-left: 90px; font-size: 9px; float: left">(назва установи)</div><div style="float:left;">Ідентифікаційний</div><div style="float: left; width: 150px;">код ЄДРПОУ</div><div style="border: 1px solid black; float: left; width: 150px;">40787802</div></div>
            <div style="text-align: left;"><p class="no_spacing">ЗАТВЕРДЖЕНО</p><p class="no_spacing">наказом Державного казначейства України</p><p class="no_spacing">від 18 грудня 2000 р. N 130</p></div>
            </div><table><tr><td style="width: 400px;"></td><td style="text-align: center;">ЗАТВЕРДЖУЮ<br/> ___________________________<br/> <span style="font-size: 14px">(підпис керівника установи)</span><br/> «___» ____________ 20_ р.</td></tr></table>
            <div style="margin-top: 80px;"><h3 style="text-align: center"><b>Акт списання № _____</b></h3><span style=" font-family: \'Times New Roman\',serif;">«___» __________ ' . $date[0] . ' р. комісія, призначена наказом по установі (організації) від «' . $date[2] . '» ' . $date[1] . ' ' . $date[0] . ' р. № 2, у
складі: ' . $listMembers . ' здійснила
перевірку матеріалів, що зробилися непридатними, та встановила, що описані нижче цінності
підлягають списанню та вилученню з обліку:</span><table class="items_table">
        <tr>
            <th>№п/п</th>
            <th>Найменування та опис(марка, ґатунок і т. д.)</th>
            <th>Одиниця виміру</th>
            <th>Кількість</th>
            <th>Ціна за одиницю</th>
            <th>Сума</th>
            <th>Місце встановлення</th>
        </tr>
        ' . $html_suitable_items . '
        </table>
        <table>
            <tr>
                <td style="width: 210px;">Усього за цим актом списано</td>
                <td style="text-align: center;">' . $total_amount . ' (' . Utils::number_to_words($total_amount) . ') одиниць (штук)</td>
            </tr>
            <tr>
                <td>предметів на загальну суму</td>
                <td style="text-align: center;">' . number_format($total_price, 2, ',', '') . ' (' . Utils::number_to_words($total_price) . $grn_corr_name . ')
                ' . $frac_part . ' (' . Utils::number_to_words($frac_part) . $k_corr_name . ')</td>
            </tr>
            <tr>
                <td>Окремі зауваження комісії</td> <td>___________________________________________________________________________</td>
            </tr>
        
    </table>
    <table>
    <tr>
            <td style="width: 120px;">Голова комісії</td>
            <td style="width: 140px; border: 1px solid white"> ' . $members[0][4] . '</td> <td style="border: 1px solid white">_________________</td> <td colspan="2" style="border: 1px solid white; width: 160px;">' . $members[0][1] . '</td>
            
        </tr>
            <tr>
            <td style="width: 120px;">Заст. гол. комісії</td>
            <td style="width: 140px; border: 1px solid white"> ' . $members[1][4] . '</td> <td style="border: 1px solid white">_________________</td> <td colspan="2" style="border: 1px solid white; width: 160px;">' . $members[1][1] . '</td>
            
        </tr>
        ' . $html_suitable_members . '
        
    </table>
        <div style="margin-top: 35px">М. В. О.  _________________   ' . $members[2][0] . '  </div>
    </div>
            
            ');
            return $mpdf -> Output('doc.pdf', 'S');
        } catch (MpdfException $e) {
        }
        return '';
    }
}