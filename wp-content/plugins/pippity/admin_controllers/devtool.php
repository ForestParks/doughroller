<?php

global $pty;
extract($_POST);
				global $wp_filesystem;

$needCreds = false;
if (isset($theme_submit)) {
		$url = wp_nonce_url('admin.php?page=pty&pty_page=devtool','pty-upload');
		$form_fields = array('theme_name', 'theme_author', 'theme_descr', 'theme_submit', 'theme_cont', 'theme_existing');
		$method = '';
		if (! (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) ) {
			if ( ! WP_Filesystem($creds) ) {
				request_filesystem_credentials($url, $method, true, false, $form_fields);
			}
			else {
				$submit_str = strtolower($theme_submit);
				if (strpos($submit_str, 'setup') !== false) {
					$conf = new StdClass();
					$theme_name = trim($theme_name);
					$file = $pty->makeSlug($theme_name, '_');
					$tdir = PTY_DIR . "/themes/$file";
					$wp_filesystem->mkdir($tdir);
					$wp_filesystem->mkdir($tdir . '/images');
					$conf->name = $theme_name;
					$conf->file = $file;
					$conf->author = $theme_author;
					$conf->descr = $theme_descr;
					$conf->copy = new StdClass();
					foreach ($theme_cont['type'] as $i => $v) {
						$cont = new StdClass();
						$cont->type = $v;
						$cont->label = $theme_cont['label'][$i];
						if (strlen($theme_cont['size'][$i])) {
							$cont->size = $theme_cont['size'][$i];
						}
						if (strlen($theme_cont['src'][$i])) {
							$cont->src = $theme_cont['src'][$i];
						}
						if (strlen($theme_cont['default'][$i])) {
							$cont->text = $theme_cont['default'][$i];
						}
						$id = $pty->makeSlug($cont->label, '_');
						$conf->copy->$id = $cont;
					}
					$conf->x = $theme_x;
					$conf->y = $theme_y;
					$f = new JsonFormatter();
					$json = trim($f->format(json_encode($conf)), "{}\n\r");
					$wp_filesystem->put_contents($tdir . "/$file.conf", $json);
				}
				else if (strpos($submit_str, 'duplicate') !== false) {
					$theme_name = trim($theme_name);
					$file = $pty->makeSlug($theme_name, '_');
					$tdir = PTY_DIR . '/themes';
					$move = $pty->movedir($tdir . '/' . $theme_existing, $tdir . '/' . $file, $wp_filesystem, 1, 1);
					$tdir .= '/' . $file;
					foreach (scandir($tdir) as $thisFile) {
						if (strpos($thisFile, $theme_existing) !== false) {
							$newFile = str_replace($theme_existing, $file, $thisFile);
							$wp_filesystem->move($tdir . '/' . $thisFile, $tdir . '/' . $newFile);
						}
					}
					$conf = json_decode('{' . file_get_contents($tdir . '/' . $file . '.conf') . '}');
					$conf->name = $theme_name;
					$conf->file = $file;
					$conf->author = $theme_author;
					$conf->descr = $theme_descr;
					$f = new JsonFormatter();
					$json = trim($f->format(json_encode($conf)), "{}\n\r");
					$wp_filesystem->put_contents($tdir . "/$file.conf", $json);
				}
			}
		}
		else {
			$needCreds = true;
		}
}

$themes = array();
foreach (scandir(PTY_DIR . '/themes') as $file) {
	if (trim($file, '.') === "") {
		continue;
	}
	$bits = explode('_', $file);
	foreach ($bits as $i => $v) {
		$bits[$i] = ucfirst($v);
	}
	$themes[$file] = implode(' ', $bits);
}

class JsonFormatter {

    public $tab = "		";

    protected $_indentLevel = 0;

    protected function _openObject()
    {
        $ret = "{\n" . str_repeat($this->tab, $this->_indentLevel + 1);
        $this->_indentLevel++;
        return $ret;
    }

    protected function _openArray()
    {
        $ret = "[\n" . str_repeat($this->tab, $this->_indentLevel + 1);
        $this->_indentLevel++;
        return $ret;
    }

    protected function _closeObject()
    {
        $this->_indentLevel--;
        return "\n" . str_repeat($this->tab, $this->_indentLevel) . "}";
    }

    protected function _closeArray()
    {
        $this->_indentLevel--;
        return "\n" . str_repeat($this->tab, $this->_indentLevel) . "]";
    }

    protected function _comma()
    {
        return ",\n" . str_repeat($this->tab, $this->_indentLevel);
    }

    protected function _colon()
    {
        return " : ";
    }

    protected function _string($str)
    {
        return "\"$str\"";
    }

    public function format($json) {
        $this->_indentLevel = 0;
        $output = '';
        $len = strlen($json);

        $jsonIdx = 0;
        while ($jsonIdx < $len) {
            $char = $json[$jsonIdx];

            if ($char === '"') {
                // copy string contents to $str
                $i = $jsonIdx + 1;
                $str = '';
                while (true) {
                    if ($json[$i] === "\\" && $json[$i+1] === '"') {
                        // copy as is
                        $str .= '\\"';
                        $i += 2;
                    } elseif ($json[$i] === '"') {
                        // end string
                        $i++;
                        break;
                    } else {
                        // copy string char
                        $str .= $json[$i];
                        $i++;
                    }
                }
                $jsonIdx = $i;
                $output .= $this->_string($str);
                continue;
            }

            switch($char) {
                case '{':
                    $output .= $this->_openObject();
                    break;
                case '[':
                    $output .= $this->_openArray();
                    break;
                case '}':
                    $output .= $this->_closeObject();
                    break;
                case ']':
                    $output .= $this->_closeArray();
                    break;
                case ',':
                    $output .= $this->_comma();
                    break;
                case ':':
                    $output .= $this->_colon();
                    break;
                default:
                    // number, true, false, or null
                    $output .= $char;
                    break;
            }
            $jsonIdx++;
        }
        return $output;
    }
}
