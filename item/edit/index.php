<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
        
    $buyplace = array();
    $query = mysql_query('SELECT * FROM `buyplace`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query)) {
        array_push($buyplace, $fetch);
    } //while
    
    $category = array();
    $query = mysql_query('SELECT * FROM `category`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query)) {
        array_push($category, $fetch);
    } //while

    $condition = array();
    $query = mysql_query('SELECT * FROM `condition`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query)) {
        array_push($condition, $fetch);
    } //while
    
    $query = mysql_query('SELECT * FROM `item` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $item = mysql_fetch_array($query);
    
    $title = 'Artikel bearbeiten';
    
    $query = mysql_query('SELECT * FROM `barcode` WHERE `id`="'.$item['barcode'].'"');
    $barcode = mysql_fetch_array($query);
    
    $customfields = array();
    $query = mysql_query('SELECT * FROM `customfield` WHERE `type`="item"');
    while($fetch=mysql_fetch_array($query))
        array_push($customfields, $fetch);
        
    $customcontent = array();
    $query = mysql_query('SELECT * FROM `customcontent` WHERE `value_id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        $customcontent[$fetch['field_id']] = $fetch;
            
    $categoryitem = array();
    $query = mysql_query('SELECT * FROM `categoryitem` WHERE `item_id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($categoryitem, $fetch);
        
    $status = array();
    $query = mysql_query('SELECT * FROM `status`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($status, $fetch);
        
    write_header($title);
    
    linenav('Zur&uuml;ck', '../', 'Speichern', 'javascript:if($(\'#form\').valid()) { document.getElementById(\'form\').submit(); }', 'icon-chevron-left', 'icon-fire icon-white');
    
    ?>
        </div>
        <form id="form" name="form" method="POST" enctype="multipart/form-data" action="save.php">
        <input type="hidden" name="id" value="<?=$id?>" />
        <div class="row-fluid">
        	<div class="span3">
        		<div class="well sidebar-nav">
        			<ul class="nav nav-list">
        				<li class="nav-header">Kategorie</li>
        				<?
        				    foreach($category as $c) {
        				        $checked = '';
        				        $query = mysql_query('SELECT * FROM `categoryitem` WHERE `category_id`="'.$c['id'].'" AND `item_id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        				        $ci = mysql_fetch_array($query);
        				        if(is_array($ci)) {
        				            $checked = 'checked';
        				        }
        				        echo '<li><input type="checkbox" name="category[]" value="'.$c['id'].'" '.$checked.'> '.$c['name'].'</li>';
        				    } //foreach
        				        
        				?>
        			</ul>
        			<br />
        			<ul class="nav nav-list">
                        <li class="nav-header">Bild einf&uuml;gen</li>
                        <li><input type="file" name="photoimg" id="photoimg" /></li>
                        <li><div id='preview'>
                        <?php
                            if(isset($item['image']) && $item['image']!='') {
                                $query = mysql_query('SELECT * FROM `image` WHERE `id`="'.$item['image'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
                                $image = mysql_fetch_array($query);
                                echo '<img src="'.dire.'_image/item/'.$image['name'].'" alt="'.$image['name'].'" />';
                            }
                        ?>
                        </div></li>
        			</ul>
        		</div>
        	</div>
        	
        	<div class="span9">
                <h1><?=$title?></h1>
                
                <hr>
                
                <div class="row-fluid">
                  <div class="span4">
                  
                    <h2>Artikel</h2>
                    <br />
                    
                    <label for="name">Name</label><input id="name" name="data[name]" type="text" value="<?=$item['name']?>" class="required" minlength="2" />
                    <label for="comments">Beschreibung</label><textarea name="data[comments]" id="comments"><?=$item['comments']?></textarea>
                    <label for="comments">Status</label>
                    <select name="data[status]">
                        <?php
                            foreach($status as $s) {
                                $selected = '';
                                if($item['status']==$s['id']) {
                                    $selected = ' selected';
                                }
                                echo '<option value="'.$s['id'].'"'.$selected.'>'.$s['status'].'</option>
                                ';
                            }
                        ?>
                    </select>
                    <label for="barcode">Barcode</label><img src="<?=dire?>barcode/?code=<?=$barcode['barcode']?>" id="barcode" alt="barcode" />
                    <input type="hidden" name="barcode" value="<?=$code?>" />
                    
                  </div><!--/span-->
                  <div class="span4">
                  
                    <h2>Anschaffung</h2>
                	    <br />
                	    
                	    <label for="datepicker">Datum</label><input id="datepicker" name="data[datepicker]" type="text" value="<?=date('d.m.Y', $item['buydate'])?>" />
                	    <label for="price">Preis</label>
                	    <div class="input-prepend">
                	                    <span class="add-on span3" style="margin-left: 0;">CHF</span>
                	                    <input id="price" name="data[buyprice]" class="span6" type="text" value="<?=$item['buyprice']?>" />
                    </div>
                    <label for="name">Zustand</label>
                    <select name="data[buycondition]">
                        <?php
                            foreach($condition as $c) {
                                $selected = '';
                                if($item['buycondition']==$c['id']) {
                                    $selected = ' selected';
                                }
                                echo '<option value="'.$c['id'].'"'.$selected.'>'.$c['name'].'</option>
                                ';
                            }
                        ?>
                    </select>
                    <label for="name">Kaufort</label>
                    <select name="data[buyplace]">
                        <?php
                            foreach($buyplace as $b) {
                                $selected = '';
                                if($item['buyplace']==$b['id']) {
                                 $selected = ' selected';
                                }
                                echo '<option value="'.$b['id'].'"'.$selected.'>'.$b['name'].'</option>
                                ';
                            }
                        ?>
                    </select>
                	    
                	  </div><!--/span-->
                  <div class="span4">
                  
                    <h2>Weiteres</h2>
                	    <br />
                	    
                	    <?php
                	        
                	        foreach($customfields as $f) {
                	            if($f['fieldtype']=='text') {
                	                echo '<label for="'.$f['name'].'">'.$f['fullname'].'</label><input type="'.$f['fieldtype'].'" name="custom['.$f['name'].']" id="'.$f['name'].'" value="'.@$customcontent[$f['id']]['value'].'" />';
                	            }
                	        }
                	        
                	    ?>
                	    
                	    <!--<label for="name">Name</label><input id="name" name="name" type="text" value="" />
                	    <label for="name">Name</label><input id="name" name="name" type="text" value="" />
                	    <label for="name">Name</label><input id="name" name="name" type="text" value="" />
                	    -->
                  </div><!--/span-->
                </div>
         	</div>
            </form>
        </div>
        <div class="row-fluid">
    	
    <?php
    
    linenav();
    
    write_footer();

?>