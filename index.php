<?php
 $select_items = [
    ["name"=>'Registered Cooperative Name',"value"=>'name'],
    ["name"=>'Registered Cooperative Date',"value"=>'prob_reg_date'],
    ["name"=>'Permanent Registration Date',"value"=>'perm_reg_date'],
    ["name"=>'Probationary Registration Number',"value"=>'pro_reg_num'],
    ["name"=>'Permanent Registration Number',"value"=>'perm_reg_num'],
    ["name"=>'District',"value"=>'district'],
    ["name"=>'Sub County',"value"=>'sub_county'],
    ["name"=>'Parish',"value"=>'parish'],
    ["name"=>'Village',"value"=>'village']
];
$sel_val ="";
$request['select_query'] = "District, Probationary Registration Number, Registered Cooperative Date, District";
$sel_val = $request['select_query'];
foreach ($select_items as $key => $value) {
    # code...
    echo $value['name'];
    echo "\n";
    echo $value['value'];
    echo "\n";
    
    echo "\n";
    echo $sel_val;
    echo "\n";
    $sel_val =  str_replace($value['name'],$value['value'],$sel_val);
    echo $sel_val;
    echo "\n";

}
$l = "District, Probationary Registration Number, Registered Cooperative Date, District";
$l = str_replace("Probationary Registration Number","j",$l);

echo $l;
echo "\n";
echo json_encode($request);