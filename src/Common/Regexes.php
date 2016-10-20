<?php

namespace Common;

class Regexes
{

    /**
     * @const REGEXP_DATE validacni regexp na cesky format data (j.n.Y) - bere v potaz prestupne roky
     */
    const DATE = "((((0?[1-9]|[1-2][0-9]|30|31)\.(0?[1,3,5,7,8]|10|12))|((0?[1-9]|[1-2][0-9]|30)\.(0?[4,6,9]|11))|((0?[1-9]|1[0-9]|2[0-8])\.(0?2)))\.((19[7-9][0-9])|(20\d{2})))|(29\.0?2\.((19(([7,9][2,6])|(8[4,8])))|(20(([1,3,5,7,9][2,6])|([0,2,4,6,8][0,4,8])))))";


    /**
     * @const REGEXP_PASSWORD validacni regexp na heslo, 8 - 20 znaku s jednim cislem a jednim velkym znakem, nebo s ne-alfanumerickym znakem
     */
    const PASSWORD = "(.*\d+.*[A-Z]+.*)|(.*[A-Z]+.*\d+.*)|(.*[!@#$%&*]+.*\d+.*)|(.*[!@#$%&*]+.*[A-Z]+.*)|(.*\d+.*[!@#$%&*]+.*)|(.*[A-Z]+.*[!@#$%&*]+.*)";


    /**
     * @const REGEXP_EMAIL validace na spravnost emailove adresy. Nevaliduje specialni znaky v uvozovkach (dnes uz se temer nepouziva)
     */
    const EMAIL = "[0-9A-Za-z\-\.!#$%&'*\+\/=?^_`{}|~]+@[0-9A-Za-z\-\.]+\.[a-zA-Z0-9]+";


    /**
     * @const REGEXP_EMAIL validace na spravnost telefonniho cisla
     */
    const PHONE = "(\+?420)?(\s*[0-9]){9}(\s*)";

}
