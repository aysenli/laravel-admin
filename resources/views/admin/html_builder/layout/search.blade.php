<div class="form-group">
    <label
            class="col-sm-3 control-label"><strong><?php echo $schema['title']; ?>
            ：</strong></label>

    <div class="col-sm-3">
        <input type="text"
               name="search_name_xxxxx"
               value="<?php echo $schema['default']; ?>"
               class="form-control <?php echo $schema['class']; ?>">


    </div>

    <div class="col-sm-3">
        <a href="javascript:void(0)" onclick='searchForSelect(this, <?php echo $schema['option'];?>)' class="btn btn-info">搜索</a>
        <select name="<?php echo $schema['name']; ?>" onchange="selectSearch(this)" class="form-control select-con">
            <option value="" >请选择</option>
        </select>
        <input type="hidden" name="<?php echo $schema['name']; ?>" value="<?php echo $data->$schema['name'] == '' ? $schema['default'] : $data->$schema['name']; ?>">
    </div>

    <div class="col-sm-3 col-sm-offset-3">
        <span class="help-block"><?php echo $schema['notice']; ?></span>

        <div class="alert alert-danger hide" role="alert">
                                                    <span class="glyphicon glyphicon-exclamation-sign"
                                                          aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            <span class="err_message"></span>
        </div>
    </div>
</div>
