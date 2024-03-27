<?php
function fetchTable($ustav)  {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://is.stuba.sk/pracoviste/prehled_temat.pl");
    curl_setopt($curl, CURLOPT_POSTFIELDS, "?pism=all;lang=sk;pracoviste=" . $ustav);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($response);

    libxml_clear_errors();
    $xpath = new DOMXPath($dom);

    $table = $xpath->query('//table[.//th[text()="Por."]]')->item(0);
    if ($table) {
        $tableHTML = $dom->saveHTML($table);
        return $tableHTML;
    } else {
        return null;
    }
}

function fetchAbstract($urlDetail) {
    $curl = curl_init();
    $url = "https://is.stuba.sk" . $urlDetail;
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($curl);
    curl_close($curl);
    // Create a DOMDocument object and load the HTML content
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $response); 

    $dom->loadHTML($response); // Use @ to suppress warnings

    // Use DOMXPath to query the document
    $xpath = new DOMXPath($dom);

    // Search for the <td> element containing "Abstrakt:"
    $query = '//td[b[contains(text(), "Abstrakt:")]]/following-sibling::td[1]';
    $abstractNodeList = $xpath->query($query);

    // Extract the content from the abstract node
    $abstract = '';
    if ($abstractNodeList->length > 0) {
        $abstract = $abstractNodeList->item(0)->nodeValue;
    }

    return $abstract;
}

function parseDataFromTable($tableHTML, $typ) {
    if (is_null($tableHTML)) return;
    
    $dom = new DOMDocument;
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $tableHTML);

    $rows = $dom->getElementsByTagName('tr');
    $data = array();

    $elements = $dom->getElementsByTagName('th');

    $zameranieExist = false;

    // Loop through the found elements and check for the desired content
    
    foreach ($elements as $element) {
        if ($element->textContent === 'Zameranie') {
            $zameranieExist = true;
            break; 
        }
    }
    
    $offset = 0;

    if (!$zameranieExist) {
        $offset = 1; 
    }

    foreach ($rows as $row) {
        // Skip if the parent is a thead
        if ($row->parentNode->nodeName === 'thead') continue;

        $cells = $row->getElementsByTagName('td');
        $rowData = array();
        
        // Access the cells directly by their indices
        $obsadenost = $cells->item(9 - $offset)->nodeValue;
        list($x, $y) = explode(' / ', $obsadenost);
        $y = ($y === '--') ? INF : intval($y);
        
        
        if ($cells->item(1)) {
            $rowData['typ'] = $cells->item(1)->nodeValue;
        } else {
            $rowData['typ'] = ""; // Assign a default value if the item doesn't exist
        }
        
        if ((intval($x) < $y) && $typ == $rowData['typ']) {
            $rowData['nazov_temy'] = $cells->item(2)->nodeValue;
            $rowData['veduci'] = $cells->item(3)->nodeValue;
            $rowData['garantujuce_miesto'] = $cells->item(4)->nodeValue;
            $rowData['program'] = $cells->item(5)->nodeValue;
            
            if ($zameranieExist) {
                $rowData["zameranie"] = $cells->item(6)->nodeValue;
            } else $rowData["zameranie"] = "";
            // Get the <a> element within the td cell
            $anchorElement = $cells->item(8 - $offset)->getElementsByTagName('a')->item(0);
            if ($anchorElement) {
                $rowData["abs_url"] = $anchorElement->getAttribute('href');
                $rowData['abstrakt'] = fetchAbstract($rowData["abs_url"]);
            } else {
                $rowData['abstrakt'] = ""; // or handle appropriately if no <a> element is found
                $rowData["abs_url"] = "";
            }
            unset($rowData["abs_url"]);
            // Add $rowData to $data[]
            $data[] = $rowData;
        }
    }

    return $data;
}
?>