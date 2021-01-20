<?php

namespace IcarosNet\BOHBasicOutputHandler;

class Output_Handler
{
    public function output($var_name)
    {
        $data            = $GLOBALS[$var_name];
        $count           = count($data);
        $type            = gettype($data);
        $var_explanation = "\n" . $this->seekData($data);
        echo highlight_string($this->build_ouput($var_name, $type, $count, $var_explanation), true);
    }

    private function build_ouput($name, $type, $count, $explanation)
    {
        return "<?php \n#output of Variable\n#this variable is of type: " . $type . "(" . $count . ")\n$" . $name . " = [" . $explanation . "];\n?>";
    }

    private function seekData($data)
    {

        $result = '';
        if (is_iterable($data)) {
            foreach ($data as $index => $value) {
                //.= $this->seekData($value);
            }
        } else {

            //return $this->textData($data);
        }
        //return ;

    }

    private function textData($data)
    {
        $type = gettype($data);
        ob_start();
        var_dump($data);
        return '(' . $type . ')' . ob_get_clean();
    }
}

