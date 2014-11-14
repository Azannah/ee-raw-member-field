<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Copyright (C) 2007 - 2011 EllisLab, Inc.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
ELLISLAB, INC. BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Except as contained in this notice, the name of EllisLab, Inc. shall not be
used in advertising or otherwise to promote the sale, use or other dealings
in this Software without prior written authorization from EllisLab, Inc.
*/

$plugin_info = array(
						'pi_name'			=> 'Raw member fields',
						'pi_version'		=> '1.0',
						'pi_author'			=> 'Matthew Johnson',
						'pi_author_url'		=> 'http://none',
						'pi_description'	=> 'Fetches a member data field from the database without any additional formatting',
						'pi_usage'			=> Raw_member_field::usage()
					);

/**
 * Raw_member_field Class
 *
 * @package			ExpressionEngine
 * @category		Plugin
 * @author			Matthew Johnson
 * @copyright		None
 * @link	  		None
 */

class Raw_member_field {

    var $return_data = '';

    
	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */

    function Raw_member_field()
    {
	    $this->EE =& get_instance();
      
      $memberFieldName = $this->EE->TMPL->fetch_param('field_name') ? $this->EE->TMPL->fetch_param('field_name') : '';
      $memberId = ee()->session->userdata('member_id');

      if (!$memberFieldName || !$memberId) return;
      
      $fieldId = $this->EE->db->select('m_field_id')
        ->from('exp_member_fields')
        ->where(array(
          'm_field_name' => $memberFieldName
        ))
        ->get()
        ->row('m_field_id');
        
      $fieldValue = $this->EE->db->select("m_field_id_{$fieldId}")
        ->from('exp_member_data')
        ->where(array(
          'member_id' => $memberId
        ))
        ->get()
        ->row("m_field_id_{$fieldId}");
                
      $this->return_data = $fieldValue;
    }
    /* END */

	// --------------------------------------------------------------------
	
	/**
	 * Usage
	 *
	 * Plugin Usage
	 *
	 * @access	public
	 * @return	string
	 */
	function usage()
	{
		
		ob_start(); 
		?>
    This plugin will query the database for the value of the logged in member data field
    based on the field_name supplied.
		
		{exp:raw_member_field field_name="Google_Plus_URL"}
	
    Version 1.0
    ******************
    - Hacked together...

		<?php
		$buffer = ob_get_contents();
	
		ob_end_clean(); 

		return $buffer;
	}

	// --------------------------------------------------------------------
	
}
// END CLASS

/* End of file pi.raw_member_field.php */
/* Location: ./system/expressionengine/third_party/no_formatting/pi.raw_member_field.php */