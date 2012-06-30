<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createUnit'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create/update\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updateUnit'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteUnit'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#SysUnit_cdunit').attr('readonly','true');  
$('#SysUnit_dscrp').attr('readonly','true');
        
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    
    $('#dg').mdmegrid('unselectAll');
    $('#SysUnit_cdunit').removeAttr('readonly');
    $('#SysUnit_cdunit').focus();
    $('#SysUnit_cdunit').val('');
    $('#SysUnit_dscrp').val('');
    $('#SysUnit_dscrp').removeAttr('readonly');
        
    $('#delBtn').linkbutton('disable'); 
    $('#trns').html('New..!');
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#sys-unit-form').serializeArray();
            if(tipe == 'New..!') $ajaxsimpan
            else $ajaxupdate
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#SysUnit_dscrp').removeAttr('readonly');
    $('#SysUnit_dscrp').val('');
    $('#SysUnit_dscrp').val(''); 
        
    $('#delBtn').linkbutton('disable');
    $('#saveBtn').linkbutton('disable');
    $('#trns').html('');
});        
   
$('#delBtn').click(function(){
    var bisa = $('#delBtn').linkbutton('options');
    if(bisa.disabled) return false;

    var data = $('#dg').mdmegrid('getSelections');
    var jmldata = data.length;
    var pesan = 'Delete '+jmldata+' selected data, Are you sure?';
    if(jmldata>0){
        if (!confirm(pesan)) return false;
	else{
            $ajaxdelete
            return false;
	}
    }
    else alert('No selected row..!');
});
        
$('#SysUnit_dscrp').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#saveBtn').click();
         $('#SysUnit_dscrp').focus();
     }
 });
        
function clickRow(){
    $('#SysUnit_cdunit').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#SysUnit_dscrp').focus();
    $('#SysUnit_dscrp').select($('#SysUnit_dscrp').length);
    $('#trns').html('Update/Delete..!');
}
           
function sukses(r,sender){
    if(r!=''){ 
        alert(r);
        return true;
    } 
    //alert('Successfully '+sender+' record..!');
    if(sender == 'delete') 
        $('#cancelBtn').click();
    else
        $('#newBtn').click();
        
    $('#dg').mdmegrid('load');
    $('#trns').html('');
}

function failed(r,sender){
    alert('Failed on '+sender+' record..!');
}
");

if (!Yii::app()->user->isGuest)
    Yii::app()->user->mmenu = "master";

$judul = "Organization";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'sys-unit-form',
            'enableAjaxValidation' => false,
                ));
        ?>

        <?php echo $form->errorSummary($model); ?>
        <table width="100%">
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdunit'); ?></td>
                <td>
                    <?php echo $form->textField($model, 'cdunit', array('size' => 13, 'maxlength' => 13)); ?>            
                </td>
            </tr>
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdorg'); ?></td>
                <td>
                    <?php //echo $form->textField($model, 'cdorg', array('size' => 13, 'maxlength' => 13)); ?>            
                    <?php
                    $listunit = CHtml::listData(SysOrg::model()->FindAll(), 'cdorg', 'dscrp');
                    echo CHtml::activeDropDownList($model, 'cdorg', $listunit);
                    ?>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:0px; height: 50px;"><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td style="border-bottom:0px; height: 50px;"><?php echo $form->textField($model, 'dscrp', array('size' => 32, 'maxlength' => 32)); ?>            </td>
            </tr>
        </table>
    </div><!-- form -->
    <?php
    $this->widget('mdmEui.grid.MdmEGrid', array(
        'id' => 'dg',
        'dataUrl' => array('dataUnit'),
        'options' => array(
            'pagination' => true,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow();}',
            'pageSize' => 10,
            'singleSelect' => true,
        ),
        'columns' => array(
            array('field' => 'cdunit', 'title' => 'Unit Code',
                'htmlOptions' => array('width' => 100, 'align' => 'center'),
                'selector' => '#SysUnit_cdunit'),
            array('field' => 'cdorg', 'title' => 'Orgn Code',
                'htmlOptions' => array('width' => 100, 'align' => 'center'),
                'selector' => '#SysUnit_cdorg'),
            array('field' => 'dscrp', 'title' => 'Description',
                'htmlOptions' => array('width' => 500),
                'selector' => '#SysUnit_dscrp'),
            array('field' => 'update_date',
                'title' => 'Last Update',
                'htmlOptions' => array('width' => 200),),
        ),
        'htmlOptions' => array(
            'fitColumns' => "true",
            'style' => "width:707px;height:300px;padding-top:0.2em;",
        )
    ));
    ?>
    <?php $this->endWidget(); ?>
</div>