
<body>
    <?php
function InputAdmin($type, $placeholder, $width, $name="", $id="input-admin",$readonly= false, $onclick = "") {
    $html = '<input class="input-admin" ';
    $html ='type="' . htmlspecialchars($type) . '" ';

    $html .= 'id="' . htmlspecialchars($id) . '" ';
    $html .= 'name="' . htmlspecialchars($name) . '" ';
    
    $html .= 'placeholder="' . htmlspecialchars($placeholder) . '" ';
    
    $html .= 'style="width:' . htmlspecialchars($width) . '%;" ';

    $html .= 'onclick="' . htmlspecialchars($onclick) . '" ';
    
    if ($readonly) {
        $html .= 'readonly ';
    }
    
    $html .= '>';
    
    echo $html;
}
?>  
</body>
</html>