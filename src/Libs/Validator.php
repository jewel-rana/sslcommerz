<?php
namespace Rajtika\SSLCommerz\Libs;

class Validator {
		public static $hasError = false;
		public static $validation_errors = array();

		public static function validate( array $params ) {
			$curClass = new static;
			foreach( $params as $key => $value ) {
				if( method_exists($curClass, $key) )
					self::$key($value);
			}

			if( self::$hasError )
				dd( self::$validation_errors );
		}

		private static function required( $field, $label = '' ) {

			if( empty( $_REQUEST[$field] ) ) {
				//set validation error message
				self::set_message( array( $field => "{$label} field is required." ) );
			}
		}

		private static function validEmail( $email ) {

		    //check email
		    if( filter_var( $email, FILTER_VALIDATE_EMAIL ) === true )
		    	return true;

	        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

	        return ( preg_match( $pattern, $email ) === 1) ? true : false;
		}

		private static function valid_email( $field, $label ) {

	        $email = trim( strtolower( $_REQUEST[$field] ) );
	        if( ! self::validEmail( $email ) ) {
	        	self::set_message( array( $field => "{$label} is not valid." ) );
	        }
		}

		private static function valid_emails( $field, $label ) {
			if (strpos( $_REQUEST[$field] , ',' ) === FALSE ) {
				if( ! self::validVmail( trim( $_REQUEST[$field] ) ) ) {
					self::set_message( array( $field => "{$label} is not valid." ) );
				}
			} else {

				foreach ( explode(',', $_REQUEST[$field] ) as $email ) {
					if ( trim( $email ) !== '' && self::validEmail( trim( $email ) ) === FALSE )
					{
						self::set_message( array( $field => "{$label} {$email} is not valid." ) );
					}
				}
			}
		}

		private static function valid_mobile( $field, $label ) {

			//Bangladeshi and Indian Number Regex
			$regex1 = "/^(?:\+88|01)?\d{11}\r?$/"; //not uses
			$regex2 = '/^(((\+|00)?880)|0)(\d){10}$/'; //used for better result
			if( ! preg_match( $regex2, $_REQUEST[$field] ) ) {
				self::set_message( array( $field => "{$label} is not valid." ) );
			}
		}

		private static function minlength( $field, $min, $label ) {
			if( strlen( $_REQUEST[$field] ) < $min ) {
				self::set_message( array( $field => "{$label} should have minimum of {$min} characters." ) );
			}
		}

		private static function maxlength( $field, $max, $label ) {
			if( strlen( $_REQUEST[$field] ) > $max ) {
				self::set_message( array( $field => "{$label} should have maximum of {$max} characters." ) );
			}
		}

		private static function exact_length( $field, $len, $label ) {
			if ( ! is_numeric($_REQUEST[$field]) && mb_strlen($_REQUEST[$field]) !== (int) $_REQUEST[$field])
			{
				self::set_message( array( $field => "{$label} should have {$len} digits." ) );
			}
		}

		private static function greater_than( $field, $min, $label ) {
			if( ! is_numeric( $_REQUEST[$field] ) && ( $_REQUEST[$field] < $min ) ) {
				self::set_message( array( $field => "{$label} should be greater than {$len}." ) );
			}
		}

		private static function greater_than_equal_to( $field, $min, $label ) {
			if( ! is_numeric($str) && ! ($str >= $min) ) {
				self::set_message( array( $field => "{$label} cannot be greater than equal to {$len}." ) );
			}
		}

		private static function less_than( $field, $max, $label ) {
			if( is_numeric( $_REQUEST[$field] ) && ! ( $_REQUEST[$field] < $max ) ) {
				self::set_message( array( $field => "{$label} cannot be greater than {$max}." ) );
			}
		}

		private static function less_than_equal_to( $field, $max, $label ) {
			if( is_numeric( $_REQUEST[$field] ) && ! ( $_REQUEST[$field] <= $max ) ) {
			self::set_message( array( $field => "{$label} cannot be less than equal to {$len}." ) );
			}
		}

		private static function regex_match( $field, $regex, $label ){
			return (bool) preg_match($regex, $str);
		}

		private static function matches( $field, $match, $label ) {
			// return isset($this->_field_data[$field], $this->_field_data[$field]['postdata'])
			// 	? ($str === $this->_field_data[$field]['postdata'])
			// 	: FALSE;
		}

		private static function decimal( $field, $label ) {
			if( ! preg_match( '/^[\-+]?[0-9]+\.[0-9]+$/', $_REQUEST[$field] ) ) {
				self::set_message( array( $field => "{$label} should be decimal value." ) );
			}
		}

		private static function alpha( $field, $label ) {
			if( ! ctype_alpha($_REQUEST[$field] ) ) {
				self::set_message( array( $field => "{$label} should be alphabetic characters only." ) );
			}
		}

		private static function alpha_numeric( $field, $label ) {
			if( ! ctype_alnum((string) $_REQUEST[$field] ) ) {
				self::set_message( array( $field => "{$label} should be alpha-numeric characters only." ) );
			}
		}

		private static function alpha_numeric_spaces( $field, $label ) {
			if( ! preg_match( '/^[A-Z0-9 ]+$/i', $_REQUEST[$field] ) ) {
				self::set_message( array( $field => "{$label} can only be alpha, numeric and spaces." ) );
			}
		}

		private static function alpha_dash( $field, $label ) {
			if( ! preg_match( '/^[a-z0-9_-]+$/i', $_REQUEST[$field] ) ) {
				self::set_message( array( $field => "{$label} can only be alpha, numeric, spaces and dashes characters." ) );
			}
		}

		private static function numeric( $field, $label ) {
			if( !preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $_REQUEST[$field] ) ) {
				self::set_message( array( $field => "{$label} should be numeric value." ) );
			}
		}

		private static function integer( $field, $label ) {
			if( ! preg_match( '/^[\-+]?[0-9]+$/', $_REQUEST[$field] ) ) {
				self::set_message( array( $field => "{$label} should be numeric value." ) );
			}
		}

		private static function differs( $field, $diff, $label ) {
			return ! (isset($this->_field_data[$field]) && $this->_field_data[$field]['postdata'] === $str);
		}

		private static function in_list( $field, $list, $label ) {
			if( ! in_array($_REQUEST[$field], explode(',', $list), TRUE) ) {
				self::set_message( array( $field => "{$label} should be in the list {$list}." ) );
			}
		}

		private static function is_natural( $field, $label ) {
			if( ! ctype_digit( ( string ) $_REQUEST[$field] ) ) {
				self::set_message( array( $field => "{$label} should be numeric value." ) );
			}
		}

		private static function is_natural_no_zero() {
			return ($str != 0 && ctype_digit((string) $str));
		}

		private static function valid_base64() {
			return (base64_encode(base64_decode($str)) === $str);
		}

		private static function valid_url( $field, $label ) {
			if ( empty( $_REQUEST[$field] ) ) {
				if ( preg_match( '/^(?:([^:]*)\:)?\/\/(.+)$/', $_REQUEST[$field], $matches ) ) {
					if ( ! empty( $matches[2] ) ) {
					return;
					}
					
					if ( in_array( strtolower( $matches[1] ), array( 'http', 'https' ), TRUE ) ) {
						return;
					}

					$str = $matches[2];

					// PHP 7 accepts IPv6 addresses within square brackets as hostnames,
					// but it appears that the PR that came in with https://bugs.php.net/bug.php?id=68039
					// was never merged into a PHP 5 branch ... https://3v4l.org/8PsSN
					if ( preg_match( '/^\[([^\]]+)\]/', $str, $matches) && ! is_php('7') && filter_var( $matches[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) !== FALSE) {
						$str = 'ipv6.host'.substr($str, strlen($matches[1]) + 2);

						if( filter_var( 'http://'.$str, FILTER_VALIDATE_URL ) === FALSE) {
							self::set_message( array( $field => "{$label} is not valid." ) );
						}
					}
				}
			}
		}

		private static function valid_ip( $field, $label ) {
			return $this->CI->input->valid_ip($ip, $which);
		}

		private static function set_message( array $error ) {
			self::$hasError = true;
			self::$validation_errors = array_merge( $error, self::$validation_errors );
		}

		private static function set_value($field, $default = '', $html_escape = TRUE)
		{
			$value = ( isset( $_REQUEST[$field] ) ) ? $_REQUEST[$field] : $default;
			// return ($html_escape) ? html_escape($value) : $value;
			return $value;
		}
	}