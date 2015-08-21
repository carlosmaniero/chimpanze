<?php
function get_queryset($model, $query, $db){
    $queryset = array();
    $i = 0;
    while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
        $queryset[$i] = new $model($db);
        $queryset[$i].set_data($row);
        $i++;
    }
    return $queryset;
}
