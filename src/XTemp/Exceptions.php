<?php

namespace XTemp;

/**
 * Exceptions
 *
 * @author Honza Poul 
 * @version Feb 20, 2014 3:30:28 PM
 */
class XTempException extends \RuntimeException {
    
}

class NotSupportedAttributeException extends XTempException {
    //put your code here
}

class OverrideAttributeException extends XTempException {
    //put your code here
}

class MissingAttributeException extends XTempException {
    //put your code here
}

class TagLibraryErrorException extends XTempException {
    //put your code here
}

class TagLibraryNotFoundException extends \Latte\CompileException {
    //put your code here
}

class ComponentNotFoundException extends XTempException {
    //put your code here
}

class ContentNotAvalibleException extends \Latte\CompileException {
    //put your code here
}

class InvalidExpressionException extends \Latte\CompileException {
    //put your code here
}

class ConverterException extends XTempException {
    //put your code here
}

class XMLParseException extends \Latte\CompileException {

    public function __construct($errors) {

        $error = $errors[0];

        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $this->message = "Warning $error->code: ";
                break;
            case LIBXML_ERR_ERROR:
                $this->message = "Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $this->message = "Fatal Error $error->code: ";
                break;
        }
        $this->message .= trim($error->message);
        $this->code = $error->code;
        $this->line = $error->line;
        
        /** todo - vyresit lepe */
        $res = \Nette\Utils\Strings::match($error->message, '~line ([0-9])+~i');
        if($res){
           $a = explode(" ", $res[0]); 
           $this->line = $a[1];
        }
        

        libxml_clear_errors();

        //parent::__construct($this->message, $this->code, $this->line);
        parent::__construct($this->message, $this->code);
    }

}
