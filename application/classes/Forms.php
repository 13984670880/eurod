<?php

class Genius_Class_Forms {

    public static function options($options, $echo = true) {
        $return = '';
        $return .= '<option value="' . $options['default']['value'] . '">' . $options['default']['title'] . '</option>';

        if (!empty($options['options'])):
            foreach ($options['options'] as $k => $item) {
                $selected = ($options['selected'] != "" AND $options['selected'] == $item['id']) ? 'selected="selected"' : '';
                $return .= '<option ' . $selected . ' value="' . $item['id'] . '">' . $item['title'] . '</option>';
            }
        endif;

        if ($echo)
            echo $return;
        else
            return $return;
    }

    public static function optionsOrderItem($options, $echo = true) {
        $atend_value = Genius_Model_Global::getMaxOrderItem($options['tablename']) + 1;

        $return = '';
        $return .= '<option value="">Ne pas changer</option>';
        $return .= '<option value="' . $options['atfirst']['value'] . '">' . $options['atfirst']['title'] . '</option>';
        $return .= '<option value="' . $atend_value . '">' . $options['atend']['title'] . '</option>';
        $return .= '<optgroup label="' . $options['optgrouplabel'] . '">';

        if (!empty($options['options'])):
            foreach ($options['options'] as $k => $item) {
                $return .= '<option class="pl10" value="' . ($item['order_item'] + 1) . '">' . $item['title'] . '</option>';
            }
        endif;

        if ($echo)
            echo $return;
        else
            return $return;
    }

    public static function optionsOrderItemImage($options, $echo = true) {
        $atend_value = Genius_Model_Global::getMaxOrderItemImage($options['id_item']) + 1;

        $return = '';
        $return .= '<option value="">Ne pas changer</option>';
        $return .= '<option value="' . $options['atfirst']['value'] . '">' . $options['atfirst']['title'] . '</option>';
        $return .= '<option value="' . $atend_value . '">' . $options['atend']['title'] . '</option>';
        $return .= '<optgroup label="' . $options['optgrouplabel'] . '">';

        if (!empty($options['options'])):
            foreach ($options['options'] as $k => $item) {
                $return .= '<option class="pl10" value="' . ($item['order_item'] + 1) . '">' . $item['title'] . '</option>';
            }
        endif;

        if ($echo)
            echo $return;
        else
            return $return;
    }
	
	public static function optionsOrderItemSlide($options, $echo = true) {
		$atend_value = Genius_Model_Global::getMaxOrderItem($options['tablename']) + 1;

        $return = '';
        $return .= '<option value="">Ne pas changer</option>';
        $return .= '<option value="' . $options['atfirst']['value'] . '">' . $options['atfirst']['title'] . '</option>';
        $return .= '<option value="' . $atend_value . '">' . $options['atend']['title'] . '</option>';
        $return .= '<optgroup label="' . $options['optgrouplabel'] . '">';

        if (!empty($options['options'])):
            foreach ($options['options'] as $k => $item) {
                $return .= '<option class="pl10" value="' . ($item['order_item'] + 1) . '">' . $item['title'] . '</option>';
            }
        endif;

        if ($echo)
            echo $return;
        else
            return $return;
    }

    public static function optionsOrderItemCategory($options, $echo = true) {
        if ($options['id_category_group'])
            $atend_value = Genius_Model_Global::getMaxOrderItemCategory($options['tablename'], $options['id_category_group']) + 1;
        else
            $atend_value = Genius_Model_Global::getMaxOrderItem($options['tablename']) + 1;

        $return = '';
        $return .= '<option value="">Ne pas changer</option>';
        $return .= '<option value="' . $options['atfirst']['value'] . '">' . $options['atfirst']['title'] . '</option>';
        $return .= '<option value="' . $atend_value . '">' . $options['atend']['title'] . '</option>';
        $return .= '<optgroup label="' . $options['optgrouplabel'] . '">';

        if (!empty($options['options'])):
            foreach ($options['options'] as $k => $item) {
                $return .= '<option class="pl10" value="' . ($item['order_item'] + 1) . '">' . $item['title'] . '</option>';
            }
        endif;

        if ($echo)
            echo $return;
        else
            return $return;
    }
	
	public static function optionsOrderItemProduct($options, $echo = true) {
        if ($options['id'])
            $atend_value = Genius_Model_Global::getMaxOrderItemProduct($options['tablename'], $options['id']) + 1;
        else
            $atend_value = Genius_Model_Global::getMaxOrderItem($options['tablename']) + 1;
			

        $return = '';
        $return .= '<option value="">Ne pas changer</option>';
        $return .= '<option value="' . $options['atfirst']['value'] . '">' . $options['atfirst']['title'] . '</option>';
        $return .= '<option value="' . $atend_value . '">' . $options['atend']['title'] . '</option>';
        $return .= '<optgroup label="' . $options['optgrouplabel'] . '">';

        if (!empty($options['options'])):
            foreach ($options['options'] as $k => $item) {
                $return .= '<option class="pl10" value="' . ($item['order_item'] + 1) . '">' . $item['title'] . '</option>';
            }
        endif;

        if ($echo)
            echo $return;
        else
            return $return;
    }

    public static function optionsModulesGroups($options, $echo = true) {
        $return = '';

        $return .= '<option value="">Choose a group</option>';
        if (!empty($options['options'])):
            foreach ($options['options'] as $k => $item) {
                $return .= '<optgroup label="' . $item['title'] . '"></optgroup>';
                if (!empty($item['categories_groups'])):
                    foreach ($item['categories_groups'] as $key => $value) {
                        $selected = ($options['selected'] != "" AND $options['selected'] == $value['id_category_group']) ? 'selected="selected"' : '';
                        $return .= '<option class="pl10" ' . $selected . ' value="' . $value['id_category_group'] . '">' . $value['title'] . '</option>';
                    }
                endif;
            }
        endif;

        if ($echo)
            echo $return;
        else
            return $return;
    }

    public static function textMultilingual($attributes, $echo = true) {
        $languages = Genius_Model_Language::getLanguages();
        $return = '';

        if (!empty($attributes)) {
            $classic = '';
            foreach ($attributes['classic'] as $key => $value) {
                $classic .= ' ' . $key . '="' . $value . '" ';
            }

            if (!empty($languages)) {
                foreach ($languages as $k => $item) {
                    $display = ($k > 0) ? 'display: none' : '';

                    $name_abbr = $attributes['custom']['name'] . "_" . $item['abbreviation'];

                    $class = $attributes['custom']['class'] . " " . $item['abbreviation'];
                    $name = $attributes['custom']['module'] . "[" . $name_abbr . "]";
                    $value = Genius_Class_Utils::idx($attributes['custom']['data'], $name_abbr, '');

                    $return .= '<input type="text" ' . $classic . ' class="' . $class . '"  name="' . $name . '" value="' . $value . '" style="' . $display . '" />';
                }
            }
        }

        if ($echo)
            echo $return;
        else
            return $return;
    }
	
	public static function text($attributes, $echo = true) {
        $return = '';

        if (!empty($attributes)) {
            $classic = '';
            foreach ($attributes['classic'] as $key => $value) {
                $classic .= ' ' . $key . '="' . $value . '" ';
            }

                    $name_abbr = $attributes['custom']['name'] ;

                    $class = $attributes['custom']['class'] ;
                    $name = $attributes['custom']['module'] . "[" . $name_abbr . "]";
                    $value = Genius_Class_Utils::idx($attributes['custom']['data'], $name_abbr, '');

                    $return .= '<input type="text" ' . $classic . ' class="' . $class . '"  name="' . $name . '" value="' . $value . '"  />';

        }

        if ($echo)
            echo $return;
        else
            return $return;
    }

    public static function textareaMultilingual($attributes, $echo = true) {
        $languages = Genius_Model_Language::getLanguages();
        $return = '';

        if (!empty($attributes)) {
            $classic = '';
            foreach ($attributes['classic'] as $key => $value) {
                $classic .= ' ' . $key . '="' . $value . '" ';
            }

            if (!empty($languages)) {
                foreach ($languages as $k => $item) {
                    $display = ($k > 0) ? 'display: none' : '';

                    $name_abbr = $attributes['custom']['name'] . "_" . $item['abbreviation'];

                    $id = $attributes['custom']['id'] . "_" . $item['abbreviation'];
                    $class = $attributes['custom']['class'] . " " . $item['abbreviation'];
                    $name = $attributes['custom']['module'] . "[" . $name_abbr . "]";
                    $value = Genius_Class_Utils::idx($attributes['custom']['data'], $name_abbr, '');

                    $return .= '<textarea ' . $classic . ' id="' . $id . '" class="' . $class . '"  name="' . $name . '" style="' . $display . '" />' . $value . '</textarea>';
                }
            }
        }

        if ($echo)
            echo $return;
        else
            return $return;
    }

    public static function boxSeo($module, $data, $echo = true) {
        // meta title
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
            )
            , 'custom' => array(
                'id' => 'seo_title'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'seo_title'
                , 'data' => $data
            )
        );
        $meta_title = Genius_Class_Forms::textMultilingual($attributes, false);

        // meta description
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'seo_meta_description'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'seo_meta_description'
                , 'data' => $data
            )
        );
        $meta_description = Genius_Class_Forms::textareaMultilingual($attributes, false);

        // meta keyword
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'seo_meta_keyword'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'seo_meta_keyword'
                , 'data' => $data
            )
        );
        $meta_keyword = Genius_Class_Forms::textareaMultilingual($attributes, false);

        // noscript
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'noscript'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'title_noscript'
                , 'data' => $data
            )
        );
        $noscript = Genius_Class_Forms::textMultilingual($attributes, false);
        // h1
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'h1'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'h1_noscript'
                , 'data' => $data
            )
        );
        $h1 = Genius_Class_Forms::textMultilingual($attributes, false);

// h1
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'h2'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'h2_noscript'
                , 'data' => $data
            )
        );
        $h2 = Genius_Class_Forms::textMultilingual($attributes, false);
        $return = '';
        $return .='
			<div class="box mt30">
			    <div class="navbar">
			        <div class="navbar-inner">
			            <h6><i class="icon-cog"></i>Search Engine Optimization (SEO)</h6>
			        </div>
			    </div>
			    
			    <div class="well tab-form-content">
			        <div class="control-group">
			            <label class="control-label" for="seo_title"> Meta Title:</label>
			            <div class="controls">' . $meta_title . '</div>
			        </div>

			        <div class="control-group">
			            <label class="control-label" for="seo_meta_description"> Meta Description:</label>
			            <div class="controls">' . $meta_description . '</div>
			        </div>

			        <div class="control-group">
			            <label class="control-label" for="seo_meta_keyword"> Meta Keyword:</label>
			            <div class="controls">' . $meta_keyword . '</div>
			        </div>
                                <div class="control-group">
			            <label class="control-label" for="noscript"> noscript:</label>
			            <div class="controls">' . $noscript . '</div>
			        </div>
                                <div class="control-group">
			            <label class="control-label" for="h1"> H1:</label>
			            <div class="controls">' . $h1 . '</div>
			        </div>                                
                                <div class="control-group">
			            <label class="control-label" for="h2"> H2:</label>
			            <div class="controls">' . $h2 . '</div>
			        </div>                                
			    </div>
			</div>
		';

        if ($echo)
            echo $return;
        else
            return $return;
    }
    public static function boxSeoMenu($module, $data, $echo = true) {
        // meta title
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
            )
            , 'custom' => array(
                'id' => 'seo_title'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'seo_title'
                , 'data' => $data
            )
        );
        $meta_title = Genius_Class_Forms::textMultilingual($attributes, false);
		
		// meta description
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'seo_meta_description'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'seo_meta_description'
                , 'data' => $data
            )
        );
        $meta_description = Genius_Class_Forms::textareaMultilingual($attributes, false);

        // meta keyword
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'seo_meta_keyword'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'seo_meta_keyword'
                , 'data' => $data
            )
        );
        $meta_keyword = Genius_Class_Forms::textareaMultilingual($attributes, false);

        // noscript
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'noscript'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'title_noscript'
                , 'data' => $data
            )
        );
        $noscript = Genius_Class_Forms::textMultilingual($attributes, false);
        // h1
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'h1'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'h1_noscript'
                , 'data' => $data
            )
        );
        $h1 = Genius_Class_Forms::textMultilingual($attributes, false);

// h1
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'h2'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'h2_noscript'
                , 'data' => $data
            )
        );
        $h2 = Genius_Class_Forms::textMultilingual($attributes, false);
		
        $return = '';
        $return .='
			<div class="box mt30">
			    <div class="navbar">
			        <div class="navbar-inner">
			            <h6><i class="icon-cog"></i>Search Engine Optimization (SEO)</h6>
			        </div>
			    </div>
			    
			    <div class="well tab-form-content">
			        <div class="control-group">
			            <label class="control-label" for="seo_title"> Meta Title:</label>
			            <div class="controls">' . $meta_title . '</div>
			        </div>
				
					<div class="control-group">
			            <label class="control-label" for="seo_meta_description"> Meta Description:</label>
			            <div class="controls">' . $meta_description . '</div>
			        </div>

			        <div class="control-group">
			            <label class="control-label" for="seo_meta_keyword"> Meta Keyword:</label>
			            <div class="controls">' . $meta_keyword . '</div>
			        </div>
                                <div class="control-group">
			            <label class="control-label" for="noscript"> noscript:</label>
			            <div class="controls">' . $noscript . '</div>
			        </div>
                                <div class="control-group">
			            <label class="control-label" for="h1"> H1:</label>
			            <div class="controls">' . $h1 . '</div>
			        </div>                                
                                <div class="control-group">
			            <label class="control-label" for="h2"> H2:</label>
			            <div class="controls">' . $h2 . '</div>
			        </div>  
				</div>
			</div>
		';

        if ($echo)
            echo $return;
        else
            return $return;
    }
    
    
    public static function seo($module, $data, $echo = true) {
        // meta title
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
            )
            , 'custom' => array(
                'id' => 'meta_titre'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'meta_titre'
                , 'data' => $data
            )
        );
        $meta_title = Genius_Class_Forms::textMultilingual($attributes, false);

        // meta keyword
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
            )
            , 'custom' => array(
                'id' => 'meta_keyword'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'meta_keyword'
                , 'data' => $data
            )
        );
        $meta_keyword = Genius_Class_Forms::textMultilingual($attributes, false);

        // meta description
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
                , 'rows' => 4
            )
            , 'custom' => array(
                'id' => 'meta_description'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'meta_description'
                , 'data' => $data
            )
        );
        $meta_description = Genius_Class_Forms::textareaMultilingual($attributes, false);

        // title noscript
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
            )
            , 'custom' => array(
                'id' => 'title_noscript'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'title_noscript'
                , 'data' => $data
            )
        );
        $title_noscript = Genius_Class_Forms::textMultilingual($attributes, false);

        // h1 noscript
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
            )
            , 'custom' => array(
                'id' => 'h1_noscript'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'h1_noscript'
                , 'data' => $data
            )
        );
        $h1_noscript = Genius_Class_Forms::textMultilingual($attributes, false);

        // h2 noscript
        $attributes = array(
            'classic' => array(
                'autocomplete' => 'off'
            )
            , 'custom' => array(
                'id' => 'h2_noscript'
                , 'class' => 'span12 multilingual'
                , 'module' => $module
                , 'name' => 'h2_noscript'
                , 'data' => $data
            )
        );
        $h2_noscript = Genius_Class_Forms::textMultilingual($attributes, false);

        $return = '';
        $return .='
			<div class="box mt30">
			    <div class="navbar">
			        <div class="navbar-inner">
			            <h6><i class="icon-cog"></i>Search Engine Optimization (SEO)</h6>
			        </div>
			    </div>
			    
			    <div class="well tab-form-content">
			        <div class="control-group">
			            <label class="control-label" for="seo_title"> Meta Title:</label>
			            <div class="controls">' . $meta_title . '</div>
			        </div>

			        <div class="control-group">
			            <label class="control-label" for="seo_meta_description"> Meta Keyword:</label>
			            <div class="controls">' . $meta_keyword . '</div>
			        </div>

			        <div class="control-group">
			            <label class="control-label" for="seo_meta_keyword"> Meta Description:</label>
			            <div class="controls">' . $meta_description . '</div>
			        </div>
			        
			        <div class="control-group">
			            <label class="control-label" for="seo_meta_keyword"> Title noscript:</label>
			            <div class="controls">' . $title_noscript . '</div>
			        </div>			        
			        
					<div class="control-group">
			            <label class="control-label" for="seo_meta_keyword"> H1 noscript:</label>
			            <div class="controls">' . $h1_noscript . '</div>
			        </div>			        			        
					<div class="control-group">
			            <label class="control-label" for="seo_meta_keyword"> H2 noscript:</label>
			            <div class="controls">' . $h2_noscript . '</div>
			        </div>			        
			    </div>
			</div>
		';

        if ($echo)
            echo $return;
        else
            return $return;
    }

    public static function boxCategory($module_id, $module, $data, $tc, $fk, $echo = true) {
        $return = '';

        $id_category_group = Genius_Model_Module::getIdCategoryGroupByModule($module_id);

        if (!empty($id_category_group)) {
            foreach ($id_category_group as $key => $value) {
                $categories = Genius_Model_Category::getCategoryBox($value);
                $checkedcat = Genius_Model_Category::getCheckedCategories($value, Genius_Class_Utils::idx($data, 'id', 0), $tc, $fk);

                $return .= '<div class="widget row-fluid">';
                $return .= '	<div class="navbar">';
                $return .= '        <div class="navbar-inner">';
                $return .= '            <h6>' . $categories['categories_name'] . '</h6>';
                $return .= '        </div>';
                $return .= '    </div>';

                $return .= '    <div class="well">';
                $return .= '        <div class="control-group">';
                $return .= '            <div>';

                if (!empty($categories)) {
                    foreach ($categories['categories_list'] as $k => $item) {
                        $checked = (in_array($item['id_category'], $checkedcat)) ? 'checked="checked"' : '';

                        $return .= '<label class="checkbox" style="cursor: pointer">';
                        $return .= '<input type="checkbox" name="' . $module . '[categories][]" id="' . $module . '_categories_' . $item['id_category'] . '" value="' . $item['id_category'] . '" class="styled" ' . $checked . '>';
                        $return .= $item['title'];
                        $return .= '</label>';
                    }
                }

                $return .= '            </div>';
                $return .= '       	</div>';
                $return .= '   	</div>';
                $return .= '</div>';
            } // endforeach
        } // endif

        if ($echo)
            echo $return;
        else
            return $return;
    }

    public static function multipleUpload($id, $upload_params) {
        $tr = Zend_Registry::get('Zend_Translate');

        $return = '';
        $return .= '<div class="widget row-fluid">';
        $return .= '	<div class="navbar"><div class="navbar-inner"><h6>' . $tr->translate("Outil pour télécharger des fichiers") . '</h6></div></div>';
        $return .= '	<div id="' . $id . '" class="well"></div>';
        $return .= '</div>';


        $return .= '<script>';
        $return .= '$(function(){';
        $return .= '	$("#' . $id . '").pluploadQueue({';
        $return .= '		runtimes : "html5,html4",';
        $return .= '		url : "' . BASE_URL . '/admin/upload/multipleupload/format/html?' . $upload_params . '",';
        $return .= '		max_file_size : "2mb",';
        $return .= '		unique_names : true,';
        $return .= '		filters : [';
        $return .= '			{title : "Image files", extensions : "jpg,jpeg,gif,png"}';
        $return .= '		]';
        $return .= '	});';

        $return .= '	var uploader = $("#' . $id . '").pluploadQueue();';
        $return .= '	uploader.bind("FileUploaded", function(Up, File, Response) {';
        $return .= '		var table = $("#data-table").dataTable();';
        $return .= '		table.fnDraw();';
        $return .= '	});';
        $return .= '});';
        $return .= '</script>';

        echo $return;
    }

}
