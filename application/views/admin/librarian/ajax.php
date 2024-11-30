<?php
                                    if (!empty($memberList)) {
                                        $count = 1;
                                        foreach ($memberList as $member) {

                                            if ($member['member_type'] == "student") {
                                                $name = $member['firstname'] . " " . $member['lastname'];
                                                $class_name = $member['class_name'];
                                                $section = $member['section'];
                                            } else {
                                                $email = $member['teacher_email'];
                                                $name = $member['teacher_name'];
                                                $sex = $member['teacher_sex'];
                                                $class_name = $member['class_name'];
                                                $section = $member['section'];
                                            }
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $member['lib_member_id']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $member['library_card_no']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $member['admission_no']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $name; ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->lang->line($member['member_type']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $class_name." ( ".$section." ) "; ?>
                                                </td>
                                                
                                                <td class="mailbox-date pull-right">
                                                    <a href="<?php echo base_url(); ?>admin/member/issue/<?php echo $member['lib_member_id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('issue_return'); ?>">
                                                        <i class="fa fa-sign-out"></i>
                                                    </a>


                                                </td>

                                            </tr>
                                            <?php
                                        }
                                        $count++;
                                    }
                                    ?>
                                    