<style>
    #guardian_address {
        width: 100%;
        max-width: 100%;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="pull-right box-tools impbtn">
                        <?php
                        if($this->rbac->hasPrivilege('import_student','can_view')){
                            ?>

                            <a href="<?php echo site_url('student/import') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> <?php echo $this->lang->line('import_student'); ?></button>
                            </a>
                            <?php
                        }
                        ?>
                    </div>

                    <form id="form1" action="<?php echo site_url('student/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body">

                            <div class="tshadow mb25 bozero">

                                <h4 class="pagetitleh2"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('admission'); ?> </h4>


                                <div class="around10">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg') ?>
                                    <?php } ?>
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="sibling_name" value="<?php echo set_value('sibling_name'); ?>" id="sibling_name_next">
                                    <input type="hidden" name="sibling_id" value="<?php echo set_value('sibling_id',0); ?>" id="sibling_id">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('admission_no'); ?></label> <small class="req"> *</small>
                                                <input autofocus="" id="admission_no" name="admission_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('admission_no'); ?>" />
                                                <span class="text-danger"><?php echo form_error('admission_no'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('roll_no'); ?></label>
                                                <input id="roll_no" name="roll_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('roll_no'); ?>" />
                                                <span class="text-danger"><?php echo form_error('roll_no'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                                <select  id="class_id" name="class_id" class="form-control"  >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($classlist as $class) {
                                                        ?>
                                                        <option value="<?php echo $class['id'] ?>"<?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                        <?php
                                                        $count++;
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                                <select  id="section_id" name="section_id" class="form-control" >
                                                    <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small>
                                                <input id="firstname" name="firstname" placeholder="" type="text" class="form-control"  value="<?php echo set_value('firstname'); ?>" />
                                                <span class="text-danger"><?php echo form_error('firstname'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('last_name'); ?></label>
                                                <input id="lastname" name="lastname" placeholder="" type="text" class="form-control"  value="<?php echo set_value('lastname'); ?>" />
                                                <span class="text-danger"><?php echo form_error('lastname'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Name in Arabic</label>
                                                <input id="name_arabic" name="name_arabic" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name_arabic'); ?>" />
                                                <span class="text-danger"><?php echo form_error('name_arabic'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small>
                                                <select class="form-control" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($genderList as $key => $value) {
                                                        ?>
                                                        <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                                                <input id="dob" name="dob" placeholder="" type="text" class="form-control"  value="<?php echo set_value('dob'); ?>" readonly="readonly"/>
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Nationality</label>
                                                <input id="name_arabic" name="nationality" placeholder="" type="text" class="form-control"  value="<?php echo set_value('nationality'); ?>" />
                                                <span class="text-danger"><?php echo form_error('nationality'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Passport Number</label>
                                                <input id="name_arabic" name="passport_number" placeholder="" type="number" class="form-control"  value="<?php echo set_value('passport_number'); ?>" />
                                                <span class="text-danger"><?php echo form_error('passport_number'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Passport Issued</label>
                                                <input id="name_arabic" name="passport_issued_from" placeholder="" type="date" class="form-control"  value="<?php echo set_value('passport_issued_from'); ?>" />
                                                <span class="text-danger"><?php echo form_error('passport_issued_from'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Passport Expiry</label>
                                                <input id="name_arabic" name="passport_expiry" placeholder="" type="date" class="form-control"  value="<?php echo set_value('passport_expiry'); ?>" />
                                                <span class="text-danger"><?php echo form_error('passport_expiry'); ?></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('category'); ?></label>

                                                <select  id="category_id" name="category_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($categorylist as $category) {
                                                        ?>
                                                        <option value="<?php echo $category['id'] ?>" <?php if (set_value('category_id') == $category['id']) echo "selected=selected"; ?>><?php echo $category['category'] ?></option>
                                                        <?php
                                                        $count++;
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('category_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('religion'); ?></label>
                                                <input id="religion" name="religion" placeholder="" type="text" class="form-control"  value="<?php echo set_value('religion'); ?>" />
                                                <span class="text-danger"><?php echo form_error('religion'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Birth Place</label><small class="req"> *</small>
                                                <input id="cast" name="birth_place" placeholder="" type="text"  class="form-control"  value="<?php echo set_value('birth_place'); ?>" />
                                                <span class="text-danger"><?php echo form_error('birth_place'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('mobile_no'); ?></label>
                                                <input id="mobileno" name="mobileno" placeholder="" type="number" class="form-control"  value="<?php echo set_value('mobileno'); ?>" />
                                                <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label>
                                                <input id="email" name="email" placeholder="" type="text" class="form-control"  value="<?php echo set_value('email'); ?>" />
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('admission_date'); ?></label>
                                                <input id="admission_date" name="admission_date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('admission_date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" />
                                                <span class="text-danger"><?php echo form_error('admission_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('photo'); ?></label>
                                                <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                        </div>

                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('blood_group'); ?></label>
                                                <?php


                                                ?>
                                                <select class="form-control" rows="3" placeholder="" name="blood_group">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($bloodgroup as $bgkey => $bgvalue) {
                                                        ?>
                                                        <option value="<?php echo $bgvalue ?>"><?php echo $bgvalue ?></option>

                                                    <?php } ?>
                                                </select>

                                                <span class="text-danger"><?php echo form_error('blood_group'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('house') ?></label>
                                                <select class="form-control" rows="3" placeholder="" name="house">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($houses as $hkey => $hvalue) {
                                                        ?>
                                                        <option value="<?php echo $hvalue["id"] ?>"><?php echo $hvalue["house_name"] ?></option>

                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('house'); ?></span>
                                            </div>
                                        </div>


                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Mother Tongue</label>
                                                <?php


                                                ?>
                                                <input type="text" name="mother_tongue" class="form-control" value="<?php echo set_value('mother_tongue'); ?>" >
                                                <span class="text-danger"><?php echo form_error('mother_tongue'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">City</label>

                                                <input type="text" id="" value="<?php echo set_value('city'); ?>" name="city" class="form-control">
                                                <span class="text-danger"><?php echo form_error('city'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Address</label>

                                                <input type="text" value="<?php echo set_value('address'); ?>" name="address" class="form-control">
                                                <span class="text-danger"><?php echo form_error('address'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Iqama Number</label>

                                                <input type="number" id="" value="<?php echo set_value('iqama_number'); ?>" name="iqama_number" class="form-control">
                                                <span class="text-danger"><?php echo form_error('iqama_number'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Iqama Expiry Date</label>

                                                <input type="date" id="" value="<?php echo set_value('iqama_expiry'); ?>" name="iqama_expiry" class="form-control">
                                                <span class="text-danger"><?php echo form_error('iqama_expiry'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Allergies</label>

                                                <input type="text" id="" value="<?php echo set_value('allergies'); ?>" name="allergies" class="form-control">
                                                <span class="text-danger"><?php echo form_error('allergies'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Medicine (In case of illness)</label>

                                                <input type="text" id="" value="<?php echo set_value('medicine'); ?>" name="medicine" class="form-control">
                                                <span class="text-danger"><?php echo form_error('medicine'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="display:none;">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('fees_discount'); ?></label>
                                                <input id="fees_discount" name="fees_discount" placeholder="" type="text" class="form-control"  value="<?php echo set_value('fees_discount', 0); ?>"  />
                                                <span class="text-danger"><?php echo form_error('fees_discount'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3 pt25">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-sm btn-primary mysiblings anchorbtn "><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('sibling'); ?></button>
                                                </div>
                                                <div class="col-md-6">
                                                    <div id='sibling_id' class="pt6"> <span id="sibling_name" class="label label-success "><?php echo set_value('sibling_name'); ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2"><?php echo $this->lang->line('parent_guardian_detail'); ?></h4>
                                <div class="around10">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('father_name'); ?></label>
                                                <input id="father_name" name="father_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_name'); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('father_phone'); ?></label>
                                                <input id="father_phone" name="father_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_phone'); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_phone'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('father_occupation'); ?></label>
                                                <input id="father_occupation" name="father_occupation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_occupation'); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_occupation'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Father Education</label>
                                                <input id="father_occupation" name="father_education" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_education'); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_education'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Father Iqama Number</label>
                                                <input id="father_occupation" name="father_iqama_number" placeholder="" type="number" class="form-control"  value="<?php echo set_value('father_iqama_number'); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_iqama_number'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Father Iqama Expiry</label>
                                                <input id="father_occupation" name="father_iqama_expiry" placeholder="" type="date" class="form-control"  value="<?php echo set_value('father_iqama_expiry'); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_iqama_expiry'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Father Passport No</label>
                                                <input id="father_occupation" name="father_passport_number" placeholder="" type="number" class="form-control"  value="<?php echo set_value('father_passport_number'); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_passport_number'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Father Passport Issued</label>
                                                <input id="father_occupation" name="father_passport_issued_from" placeholder="" type="date" class="form-control"  value="<?php echo set_value('father_passport_issued_from'); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_passport_issued_from'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Father Passport Expiry</label>
                                                <input id="father_occupation" name="father_passport_expiry" placeholder="" type="date" class="form-control"  value="<?php echo set_value('father_passport_expiry'); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_passport_expiry'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('photo'); ?></label>
                                                <div><input class="filestyle form-control" type='file' name='father_pic' id="file" size='20' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_name'); ?></label>
                                                <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_name'); ?>" />
                                                <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_phone'); ?></label>
                                                <input id="mother_phone" name="mother_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_phone'); ?>" />
                                                <span class="text-danger"><?php echo form_error('mother_phone'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_occupation'); ?></label>
                                                <input id="mother_occupation" name="mother_occupation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_occupation'); ?>" />
                                                <span class="text-danger"><?php echo form_error('mother_occupation'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Mother Education</label>
                                                <input id="mother_occupation" name="mother_education" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_education'); ?>" />
                                                <span class="text-danger"><?php echo form_error('mother_education'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('photo'); ?></label>
                                                <div><input class="filestyle form-control" type='file' name='mother_pic' id="file" size='20' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label><?php echo $this->lang->line('if_guardian_is'); ?><small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="guardian_is" <?php
                                                echo set_value('guardian_is') == "father" ? "checked" : "";
                                                ?>   value="father"> <?php echo $this->lang->line('father'); ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="guardian_is" <?php
                                                echo set_value('guardian_is') == "mother" ? "checked" : "";
                                                ?>   value="mother"> <?php echo $this->lang->line('mother'); ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="guardian_is" <?php
                                                echo set_value('guardian_is') == "other" ? "checked" : "";
                                                ?>   value="other"> <?php echo $this->lang->line('other'); ?>
                                            </label>
                                            <span class="text-danger"><?php echo form_error('guardian_is'); ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_name'); ?></label><small class="req"> *</small>
                                                <input id="guardian_name" name="guardian_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_name'); ?>" />
                                                <span class="text-danger"><?php echo form_error('guardian_name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_relation'); ?></label>
                                                <input id="guardian_relation" name="guardian_relation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_relation'); ?>" />
                                                <span class="text-danger"><?php echo form_error('guardian_relation'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_phone'); ?></label><small class="req"> *</small>
                                                <input id="guardian_phone" name="guardian_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_phone'); ?>" />
                                                <span class="text-danger"><?php echo form_error('guardian_phone'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_id'); ?><small class="req"> *</small></label>
                                                <input id="guardian_id" required name="guardian_id" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_id'); ?>" />
                                                <span class="text-danger"><?php echo form_error('guardian_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_occupation'); ?></label>
                                                <input id="guardian_occupation" name="guardian_occupation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_occupation'); ?>" />
                                                <span class="text-danger"><?php echo form_error('guardian_occupation'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_email'); ?></label>
                                                <input id="guardian_email" name="guardian_email" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_email'); ?>" />
                                                <span class="text-danger"><?php echo form_error('guardian_email'); ?></span>
                                            </div>

                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_address'); ?></label>
                                                <textarea id="guardian_address" name="guardian_address" placeholder="" class="form-control" rows="2"><?php echo set_value('guardian_address'); ?></textarea>
                                                <span class="text-danger"><?php echo form_error('guardian_address'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('guardian'); ?> <?php echo $this->lang->line('photo'); ?></label>
                                                <div><input class="filestyle form-control" type='file' name='guardian_pic' id="file" size='20' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="box-group collapsed-box">
                                <div class="panel box box-success collapsed-box">
                                    <div class="box-header with-border">

                                        <a data-widget="collapse" data-original-title="Collapse" class="collapsed btn boxplus">
                                            <i class="fa fa-fw fa-plus"></i><?php echo $this->lang->line('add_more_details'); ?>
                                        </a>

                                    </div>
                                    <div class="box-body">
                                        <div class="tshadow mb25 bozero">
                                            <h4 class="pagetitleh2"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('address'); ?> <?php echo $this->lang->line('details'); ?></h4>

                                            <div class="row around10">

                                                <div class="col-md-6">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="autofill_current_address" onclick="return auto_fill_guardian_address();">
                                                            <?php echo $this->lang->line('if_guardian_address_is_current_address'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('current_address'); ?></label>
                                                        <textarea id="current_address" name="current_address" placeholder=""  class="form-control" ><?php echo set_value('current_address'); ?><?php echo set_value('current_address'); ?></textarea>
                                                        <span class="text-danger"><?php echo form_error('current_address'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="autofill_address"onclick="return auto_fill_address();">
                                                            <?php echo $this->lang->line('if_permanent_address_is_current_address'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('permanent_address'); ?></label>
                                                        <textarea id="permanent_address" name="permanent_address" placeholder="" class="form-control"></textarea>
                                                        <span class="text-danger"><?php echo form_error('permanent_address'); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <?php

                                        if($this->module_lib->hasActive('transport')){
                                            ?>
                                            <div class="tshadow mb25 bozero">
                                                <h4 class="pagetitleh2">
                                                    <?php echo $this->lang->line('transport') . " " . $this->lang->line('details'); ?>
                                                </h4>

                                                <div class="row around10">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('route_list'); ?></label>
                                                            <select class="form-control" id="vehroute_id" name="vehroute_id">

                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php
                                                                foreach ($vehroutelist as $vehroute) {
                                                                    ?>
                                                                    <optgroup label=" <?php echo $vehroute->route_title; ?>">
                                                                        <?php
                                                                        $vehicles = $vehroute->vehicles;
                                                                        if (!empty($vehicles)) {
                                                                            foreach ($vehicles as $key => $value) {
                                                                                ?>

                                                                                <option value="<?php echo $value->vec_route_id ?>" <?php echo set_select('vehroute_id', $value->vec_route_id); ?> data-fee="<?php echo $vehroute->fare; ?>">
                                                                                    <?php echo $value->vehicle_no ?>
                                                                                </option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </optgroup>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('transport_fees'); ?></span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php

                                        if($this->module_lib->hasActive('hostel')){
                                            ?>
                                            <div class="tshadow mb25 bozero">
                                                <h4 class="pagetitleh2">
                                                    <?php echo $this->lang->line('hostel'); ?></label> <?php echo $this->lang->line('details'); ?></label>
                                                </h4>

                                                <div class="row around10">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('hostel'); ?></label>

                                                            <select class="form-control" id="hostel_id" name="hostel_id">

                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php

                                                                foreach ($hostelList as $hostel_key => $hostel_value) {
                                                                    ?>

                                                                    <option value="<?php echo $hostel_value['id'] ?>" <?php echo set_select('hostel_id', $hostel_value['id']); ?>>
                                                                        <?php

                                                                        echo $hostel_value['hostel_name']; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('hostel_id'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('room_no'); ?></label>
                                                            <select  id="hostel_room_id" name="hostel_room_id" class="form-control" >
                                                                <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('hostel_room_id'); ?></span>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="tshadow mb25 bozero">
                                            <h4 class="pagetitleh2"><?php echo $this->lang->line('miscellaneous_details'); ?>
                                            </h4>

                                            <div class="row around10 d-none">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_account_no'); ?></label>
                                                        <input id="bank_account_no" name="bank_account_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('bank_account_no'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_name'); ?></label>
                                                        <input id="bank_name" name="bank_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('bank_name'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('bank_name'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('ifsc_code'); ?></label>
                                                        <input id="ifsc_code" name="ifsc_code" placeholder="" type="text" class="form-control"  value="<?php echo set_value('ifsc_code'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row around10">
                                                <div class="col-md-6 d-none">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Medical Insurance Number</label>
                                                        <input id="ifsc_code" name="insurance_number" placeholder="" type="number" class="form-control"  value="<?php echo set_value('insurance_number'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('insurance_number'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 d-none">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Insurance Company Name</label>
                                                        <input id="ifsc_code" name="insurance_company_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('insurance_company_name'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('insurance_company_name'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 d-none">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Insurance Policy Number</label>
                                                        <input id="ifsc_code" name="insurance_policy_number" placeholder="" type="number" class="form-control"  value="<?php echo set_value('insurance_policy_number'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('insurance_policy_number'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 d-none">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Insurance Contact Number</label>
                                                        <input id="ifsc_code" name="insurance_contact_number" placeholder="" type="number" class="form-control"  value="<?php echo set_value('insurance_contact_number'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('insurance_contact_number'); ?></span>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('previous_school_details'); ?></label>
                                                        <textarea class="form-control" rows="3" placeholder="" name="previous_school"></textarea>
                                                        <span class="text-danger"><?php echo form_error('previous_school'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('note'); ?></label>
                                                        <textarea class="form-control" rows="3" placeholder="" name="note"></textarea>
                                                        <span class="text-danger"><?php echo form_error('note'); ?></span>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div id='upload_documents_hide_show'>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="tshadow bozero">
                                                        <h4 class="pagetitleh2"><?php echo $this->lang->line('upload_documents'); ?></h4>

                                                        <div class="row around10">
                                                            <div class="col-md-6">
                                                                <table class="table">
                                                                    <tbody><tr>
                                                                        <th style="width: 10px">#</th>
                                                                        <th><?php echo $this->lang->line('title'); ?></th>
                                                                        <th><?php echo $this->lang->line('documents'); ?></th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>1.</td>
                                                                        <td><input type="text" name='first_title' class="form-control" placeholder=""></td>
                                                                        <td>
                                                                            <input class="filestyle form-control" type='file' name='first_doc' id="doc1" >
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>2.</td>
                                                                        <td><input type="text" name='second_title' class="form-control" placeholder=""></td>
                                                                        <td>
                                                                            <input class="filestyle form-control" type='file' name='second_doc' id="doc1" >
                                                                        </td>
                                                                    </tr>
                                                                    <!--tr>
                                                                        <td>3.</td>
                                                                        <td><input type="text" name='third_title' class="form-control" placeholder=""></td>
                                                                        <td>
                                                                            <input class="filestyle form-control" type='file' name='third_doc' id="doc1" >
                                                                        </td>
                                                                    </tr-->
                                                                    </tbody></table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <table class="table">
                                                                    <tbody><tr>
                                                                        <th style="width: 10px">#</th>
                                                                        <th><?php echo $this->lang->line('title'); ?></th>
                                                                        <th><?php echo $this->lang->line('documents'); ?></th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>3.</td>
                                                                        <td><input type="text" name='fourth_title' class="form-control" placeholder=""></td>
                                                                        <td>
                                                                            <input class="filestyle form-control" type='file' name='fourth_doc' id="doc1" >
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>4.</td>
                                                                        <td><input type="text" name='fifth_title' class="form-control" placeholder=""></td>
                                                                        <td>
                                                                            <input class="filestyle form-control" type='file' name='fifth_doc' id="doc1" >
                                                                        </td>
                                                                    </tr>
                                                                    </tbody></table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
</div>
</section>
</div>



<div class="modal fade" id="mySiblingModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center modal_title"></h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="box-body">
                        <div class="sibling_msg">

                        </div>
                        <input  type="hidden" class="form-control" id="transport_student_session_id"  value="0" readonly="readonly"/>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label"><?php echo $this->lang->line('class'); ?></label>
                            <div class="col-sm-10">
                                <select  id="sibiling_class_id" name="sibiling_class_id" class="form-control"  >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($classlist as $class) {
                                        ?>
                                        <option value="<?php echo $class['id'] ?>"<?php if (set_value('sibiling_class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label"><?php echo $this->lang->line('section'); ?></label>
                            <div class="col-sm-10">
                                <select  id="sibiling_section_id" name="sibiling_section_id" class="form-control" >
                                    <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger" id="transport_amount_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label"><?php echo $this->lang->line('student'); ?>
                            </label>

                            <div class="col-sm-10">
                                <select  id="sibiling_student_id" name="sibiling_student_id" class="form-control" >
                                    <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger" id="transport_amount_fine_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn btn-primary add_sibling" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i class="fa fa-user"></i> <?php echo $this->lang->line('add'); ?></button>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">


    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id',0) ?>';
        var hostel_id = $('#hostel_id').val();
        var hostel_room_id = '<?php echo set_value('hostel_room_id',0) ?>';
        getHostel(hostel_id, hostel_room_id);
        getSectionByClass(class_id, section_id);

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0);
        });

        $('#dob,#admission_date,#measure_date').datepicker({
            format: date_format,
            autoclose: true
        });

        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });


        $(document).on('change', '#hostel_id', function (e) {
            var hostel_id = $(this).val();
            getHostel(hostel_id, 0);

        });

        function getSectionByClass(class_id, section_id) {

            if (class_id != "" ) {
                $('#section_id').html("");
                var base_url = '<?php echo base_url() ?>';
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                var url = "<?php $userdata = $this->customlib->getUserData();
                    if(($userdata["role_id"] == 2)){ echo "getClassTeacherSection" ; }else{ echo "getByClass"; } ?>";

                $.ajax({
                    type: "GET",
                    url: base_url + "sections/"+url,
                    data: {'class_id': class_id},
                    dataType: "json",
                    beforeSend: function(){
                        $('#section_id').addClass('dropdownloading');
                    },
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var sel = "";
                            if (section_id == obj.section_id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                        });
                        $('#section_id').append(div_data);
                    },
                    complete: function(){
                        $('#section_id').removeClass('dropdownloading');
                    }
                });
            }
        }


        function getHostel(hostel_id, hostel_room_id) {
            if(hostel_room_id == ""){
                hostel_room_id=0;
            }

            if (hostel_id != "") {

                $('#hostel_room_id').html("");


                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "GET",
                    url: baseurl + "admin/hostelroom/getRoom",
                    data: {'hostel_id': hostel_id},
                    dataType: "json",
                    beforeSend: function(){
                        $('#hostel_room_id').addClass('dropdownloading');
                    },
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var sel = "";
                            if (hostel_room_id == obj.id) {
                                sel = "selected";
                            }

                            div_data += "<option value=" + obj.id + " " + sel + ">" + obj.room_no+" ("+obj.room_type+")" + "</option>";

                        });
                        $('#hostel_room_id').append(div_data);
                    },
                    complete: function(){
                        $('#hostel_room_id').removeClass('dropdownloading');
                    }
                });
            }
        }

    });
    function auto_fill_guardian_address() {
        if ($("#autofill_current_address").is(':checked'))
        {
            $('#current_address').val($('#guardian_address').val());
        }
    }
    function auto_fill_address() {
        if ($("#autofill_address").is(':checked'))
        {
            $('#permanent_address').val($('#current_address').val());
        }
    }
    $('input:radio[name="guardian_is"]').change(
        function () {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value == "father") {
                    $('#guardian_name').val($('#father_name').val());
                    $('#guardian_phone').val($('#father_phone').val());
                    $('#guardian_occupation').val($('#father_occupation').val());
                    $('#guardian_relation').val("Father")
                } else if (value == "mother") {
                    $('#guardian_name').val($('#mother_name').val());
                    $('#guardian_phone').val($('#mother_phone').val());
                    $('#guardian_occupation').val($('#mother_occupation').val());
                    $('#guardian_relation').val("Mother")
                } else {
                    $('#guardian_name').val("");
                    $('#guardian_phone').val("");
                    $('#guardian_occupation').val("");
                    $('#guardian_relation').val("")
                }
            }
        });


</script>

<script type="text/javascript">
    $(".mysiblings").click(function () {
        $('.sibling_msg').html("");
        $('.modal_title').html('<b>' + "<?php echo $this->lang->line('sibling'); ?>" + '</b>');
        $('#mySiblingModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });
</script>

<script type="text/javascript">

    $(document).on('change', '#sibiling_class_id', function (e) {
        $('#sibiling_section_id').html("");
        var class_id = $(this).val();
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                });
                $('#sibiling_section_id').append(div_data);
            }
        });
    });

    $(document).on('change', '#sibiling_section_id', function (e) {
        getStudentsByClassAndSection();
    });

    function getStudentsByClassAndSection() {
        $('#sibiling_student_id').html("");
        var class_id = $('#sibiling_class_id').val();
        var section_id = $('#sibiling_section_id').val();
        var student_id = '<?php echo set_value('student_id') ?>';
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "student/getByClassAndSection",
            data: {'class_id': class_id, 'section_id': section_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    var sel = "";
                    if (section_id == obj.section_id) {
                        sel = "selected=selected";
                    }
                    div_data += "<option value=" + obj.id + ">" + obj.firstname + " " + obj.lastname + "</option>";
                });
                $('#sibiling_student_id').append(div_data);
            }
        });
    }

    $(document).on('click', '.add_sibling', function () {
        var student_id = $('#sibiling_student_id').val();
        var base_url = '<?php echo base_url() ?>';
        if (student_id.length > 0) {
            $.ajax({
                type: "GET",
                url: base_url + "student/getStudentRecordByID",
                data: {'student_id': student_id},
                dataType: "json",
                success: function (data) {
                    $('#sibling_name').text("Sibling: " + data.firstname + " " + data.lastname);
                    $('#sibling_name_next').val(data.firstname + " " + data.lastname);
                    $('#sibling_id').val(student_id);
                    $('#father_name').val(data.father_name);
                    $('#father_phone').val(data.father_phone);
                    $('#father_occupation').val(data.father_occupation);
                    $('#mother_name').val(data.mother_name);
                    $('#mother_phone').val(data.mother_phone);
                    $('#mother_occupation').val(data.mother_occupation);
                    $('#guardian_name').val(data.guardian_name);
                    $('#guardian_relation').val(data.guardian_relation);
                    $('#guardian_address').val(data.guardian_address);
                    $('#guardian_phone').val(data.guardian_phone);
                    $('#guardian_id').val(data.guardian_id);
                    $('#state').val(data.state);
                    $('#city').val(data.city);
                    $('#pincode').val(data.pincode);
                    $('#current_address').val(data.current_address);
                    $('#permanent_address').val(data.permanent_address);
                    $('#guardian_occupation').val(data.guardian_occupation);
                    $("input[name=guardian_is][value='" + data.guardian_is + "']").prop("checked", true);
                    $('#mySiblingModal').modal('hide');
                }
            });
        } else {
            $('.sibling_msg').html("<div class='alert alert-danger'>No Student Selected</div>");
        }

    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>    