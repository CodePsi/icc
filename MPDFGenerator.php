<?php

namespace Icc;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Icc\Utils\Utils;
require __DIR__ . "/../vendor/autoload.php";

class MPDFGenerator {
    private static $mpdfGeneratorInstance = null;


    /**
     * Singleton pattern implementation for the MPDFGenerator class
     *
     * @return MPDFGenerator|null
     */
    public static function getInstance() {
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
     * @param $number_id
     * @param $date
     * @param $time
     * @param $responsiblePerson
     * @param $position
     * @param $phone
     * @param $building
     * @param $auditorium
     * @param $inventoryNumbers
     * @param $task
     * @return string document information for further encoding (mostly it is used for passing data between client and server and just encoding retrieved information)
     */
    public function generateRequest($number_id, $date, $time, $responsiblePerson, $position, $phone, $building, $auditorium, $inventoryNumbers, $task) {
        try {
            $mpdf = new \Mpdf\Mpdf([

            ]);
            $inventory_number = '';
            foreach ($inventoryNumbers as $item) {
                $inventory_number .= $item . ', ';
            }
            if (empty($inventory_number)) {
                $inventory_number = '________________';
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
            </style>
            <body>
                <header class="header">
                    Заявка на виконання робіт / обслуговування комп`ютерної техніки №' . $number_id . '       
                </header>
                <div class="main_information">
                    <p><b>Дата:</b>'. $date .'<b> Час:</b>' . $time . '</p>
                    <p><b>Відповідальна особа: </b>' . $responsiblePerson . ', ' . $position . '</p>
                    <p><b>Контактний телефон:</b>'. $phone .'</p>
                    <p><b>Корпус:</b>' . $building . '<b> Аудиторія:</b>' . $auditorium . '</p>
                    <p><b>Інв. номер техніки:</b>'. $inventory_number .'</p>
                    <p><b>Завдання / ознаки несправності КТ:</b></p>
                    <table class="task_table"><tr><td>' . $task . '</td></tr></table>
                    <table class="standard_table"><tr><td style="width: 500px"><b>Огляд (ремонт): негайно/ _______________ о _______________</b></td><td><b>Шестопалова О. В.</b></td></tr></table>
                    <p><b>Результати роботи огляду інженером ІОЦ:</b></p>
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
                </div>
            </body>
            ');
            return $mpdf -> Output("doc.pdf", "S");
        } catch (MpdfException $e) {
        }
        return "ERROR";
    }

    public function generateTechnicalPass($data, $number_of_pass, $building, $auditorium, $inventory_number) {
        try {
            $mpdf = new \Mpdf\Mpdf([]);
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
            </style>
            
            <body>
                <div class="main_information">
                <table class="standard_table"><tr><td style="width: 280px"><b>Дата: </b>' . $data . '</td><td><b>Талон на технічне обслуговування №' . $number_of_pass . '</b></td></tr></table>
                <p><b>Корпус</b> ____________ <b>аудиторія</b> ____________   <b>Інв. номер комп’ютера</b> ____________</p>
                <p><b>Наявність гарантії: нема/до</b> ____________</p>
                <p>Зауваження (фахівець ІОЦ)</p>
                <p>_______________________________________________________________________________________________________</p>
                <p>_______________________________________________________________________________________________________</p>
                <p>_______________________________________________________________________________________________________</p>
                <p>Фахівець ІОЦ _________________________________</p>
                <p><b>Відповідальна особа</b> ____________________ <b>дата</b> ____________________</p>
                <p>_______________________________________________________________________________________________________</p>
                <p>_______________________________________________________________________________________________________</p>
                </div>
            </body>');
            $mpdf -> Output();
        } catch (MpdfException $e) {
        }
    }

    /**
     * @param $date string pattern "Month (In ukrainian) year"
     * @param $responsible_person string
     * @param $head string
     * @param $members string array
     * @param $items two-dimensional array
     * @return string|null
     */
    public function actOfInstallation(string $date, string $responsible_person, string $head, array $members, $items) {
        try {
            $mpdf = new \Mpdf\Mpdf();
            $html_suitable_items = "";
            $index_number = 0;
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
            <td>Матеріально відповідальна особа::</td>
            <td>' . $responsible_person . '</td>
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
            <td>1. ' . $members[0] . '</td>
        </tr>
        <tr>
            <td></td>
            <td>2. ' . $members[1] . '</td>
        </tr>
        <tr>
            <td></td>
            <td>3. ' . $members[2] . '</td>
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
            <td>1. ' . $members[0] . '</td>
        </tr>
        <tr>
            <td></td>
            <td>2. ' . $members[1] . '</td>
        </tr>
        <tr>
            <td></td>
            <td>3. ' . $members[2] . '</td>
        </tr>
        <tr>
        <td>Матеріально відповідальна особа</td>
        <td>' . $responsible_person . '   </td>
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
     * @return string|null
     */
    public function writeOffAct(array $items, array $members): string {
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
        $frac_part = explode('.', $total_price)[1];
        if (empty($frac_part)) $frac_part = 0;
        if ($total_price % 10 == 1) $grn_corr_name = ' гривня';
        else if ($total_price % 10 > 4 || $total_price % 10 == 0 ) $grn_corr_name = ' гривень';
        else $grn_corr_name = ' гривені';
        if ($frac_part % 10 == 1) $k_corr_name = ' копійка';
        else if ($frac_part % 10 > 4 || $frac_part % 10 == 0 ) $k_corr_name = ' копійок';
        else $k_corr_name = ' копійки';
        $html_suitable_members = '';
        foreach ($members as $member) {
            $html_suitable_members .= '<tr style="border-bottom: 1px solid white;">
        <td></td>
        <td></td>
        <td style="border: 1px solid white">' . $member . '</td> <td style="border: 1px solid white">_________________</td> <td style="border: 1px solid white">Шестопалова О. В.</td> 
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
            <div style="margin-top: 80px;"><h3 style="text-align: center"><b>Акт списання № _____</b></h3><span style=" font-family: \'Times New Roman\',serif;">«___» __________ <u>2019 р.</u> комісія, призначена наказом по установі (організації) від «02» січня 2019 р. № 2, у
складі: проректор з АГР Золотницький М. Я., начальник ЕТВ Лозова В. І., начальник ІОЦ Шестопалова О. В.,
бухгалтер Козак О. В., старший викладач кафедри математики та методики її навчання Бобилєв Д. Є. здійснила
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
        <tr>
            <td colspan="2">Усього за цим актом списано</td>
            <td style="text-align: center;" colspan="5">' . $total_amount . ' (' . Utils::number_to_words($total_amount) . ') одиниць (штук)</td>
        </tr>
        <tr>
            <td colspan="2">предметів на загальну суму</td>
            <td style="text-align: center;" colspan="4">' . number_format($total_price, 2, ',', '') . ' (' . Utils::number_to_words($total_price) . $grn_corr_name . ')</td>
            <td>' . $frac_part . ' (' . Utils::number_to_words($frac_part) . $k_corr_name . ')</td>
        </tr>
        <tr>
            <td colspan="7">Окремі зауваження комісії___________________________________________________________________________</td>
        </tr>
        
    </table><table class="items_table" style="border-bottom: 1px solid white;">
    <tr>
            <td style="width: 200px;"></td>
            <td style="width: 120px;">Голова комісії</td>
            <td style="width: 140px; border: 1px solid white">Проректор з АГР </td> <td style="border: 1px solid white">_________________</td> <td colspan="2" style="border: 1px solid white; width: 160px;">Золотницький М. Я.</td>
            
        </tr>
        <tr style="border-bottom: 1px solid white">
            <td style="vertical-align: bottom;"></td>
            <td>Члени комісії</td>
            <td style="border: 1px solid white">Начальник ЕТВ</td> <td style="border: 1px solid white">_________________</td> <td style="border: 1px solid white;">Лозова В. І.</td> 
        </tr>
        <tr style="border-bottom: 1px solid white;">
            <td></td>
            <td></td>
            <td style="border: 1px solid white">Начальник ІОЦ</td> <td style="border: 1px solid white">_________________</td> <td style="border: 1px solid white">Шестопалова О. В.</td> 
        </tr>
        <tr style="border-bottom: 1px solid white;">
            <td></td>
            <td></td> 
            <td style="border: 1px solid white">Бухгалтер</td> <td style="border: 1px solid white;">_________________</td> <td style="border: 1px solid white">Козак О. В.</td> 
        </tr>
        <tr style="border-bottom: 1px solid white">
            <td></td>
            <td></td>
            <td style="border: 1px solid white">Ст. викладач каф. мат-ки та методики її навчання</td> <td style="border: 1px solid white">_________________</td> <td style="border: 1px solid white">Бобилєв Д. Є.</td> 
        </tr>
        <tr style="border-top: 1px solid white">
            <td>М. В. О.: _______________</td>
            <td></td>
            <td style="border: 1px solid white;">Шестопалова Оксана Василівна</td>
            <td style="border: 1px solid white;"></td>
            <td style="border: 1px solid white;"></td>
        </tr>
    </table></div>
            
            ');
            return $mpdf -> Output('doc.pdf', 'S');
        } catch (MpdfException $e) {
        }
        return null;
    }
}