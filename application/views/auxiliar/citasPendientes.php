

<div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> Aceptar / rechazar citas</h4>
        </header>

       

        <div class="panel-body">
                
            


                <div class="panel-body mb-md">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr>
                                <th><?=translate('Codigo')?></th>

                                <th><?=translate('name')?></th>
                                <th><?=translate('Aula')?></th>

                                <th><?=translate('fecha')?></th>

                                <th><?=translate('Día')?></th>

                                <th><?=translate('Hora Inicio Cita')?></th>
                                <th><?=translate('Hora Termino Cita')?></th>

                                <th><?=translate('Grado')?></th>
                                <th><?=translate('Seccion')?></th>
                                <th><?=translate('Ver información del estudiante')?></th>
                               
                                <th> Resultados</th>


                            </tr>
                            
                        </thead>
                        <tbody>
                            <?php
                            foreach($students as $row):
                            ?>

                            <tr>
                                <td><?php echo $row['register_no'];?></td>

                                <td><?php echo $row['last_name'].' '.$row['first_name'];?></td>
                                <td><?php echo $row['nombre_aula'];?></td>

                                <td><?php echo $row['fecha'];?></td>

                                <td><?php echo $row['dia_semana'];?></td>

                                <td><?php echo  date('H:i', strtotime($row['hora_inicio'])) ;?></td>
                                <td><?php echo date('H:i', strtotime($row['hora_fin']));?></td>
                                <td><?php echo $row['class_name'];?></td>
                                <td><?php echo $row['section_name'];?></td>


                                <td>
                                <button type="button" class="btn btn-info btn-circle icon" data-toggle="modal" data-target="#exampleModal<?= $row['schedule_id'] ?>">
                                        <i class="fas fa-question"></i>
                                    </button>
                                </td>

                                <div class="modal fade" id="exampleModal<?= $row['schedule_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">


                                                    <!-- academic details-->
                                                    <div class="headers-line">
                                                        <i class="fas fa-school"></i> <?=translate('academic_details')?>
                                                    </div>
                                                
                                                    <div class="row">
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('branch')?></label>
                                                                <p class="text-dark"><?=$row['branch_name']?></p>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('academic_year')?></label>
                                                                <p class="text-dark"><?=$row['school_year']?></p>

                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('register_no')?></label>
                                                                <p class="text-dark"><?=$row['register_no']?></p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('roll')?></label>
                                                                <p class="text-dark"><?=$row['roll']?></p>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('admission_date')?></label>
                                                                <p class="text-dark"><?php echo $row['admission_date'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('class')?></label>
                                                                <p class="text-dark"><?php echo $row['class_name'] ?></p>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
                                                                <p class="text-dark"><?php echo $row['section_name'] ?></p>

                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('category')?></label>
                                                                <p class="text-dark"><?php echo $row['category_name'] ?></p>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="headers-line mt-md">
                                                        <i class="fas fa-user-check"></i> <?=translate('student_details')?>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('first_name')?> <span class="required">*</span></label>
                                                                <p class="text-dark"><?php echo $row['first_name'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('last_name')?></label>
                                                                <p class="text-dark"><?php echo $row['last_name'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('gender')?></label>
                                                                <p class="text-dark"><?php
                                                                $arrayGender = array(
                                                                'male' => translate('male'),
                                                                'female' => translate('female')
                                                                );
                                                                echo $arrayGender[$row['gender']];
                                                                ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('blood_group')?></label>
                                                                <p class="text-dark"><?php
                                                                    $bloodArray = $this->app_lib->getBloodgroup();
                                                                    echo $bloodArray[$row['blood_group']];
                                                                ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('birthday')?></label>
                                                                <p class="text-dark"><?php echo $row['birthday'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('mother_tongue')?></label>
                                                                <p class="text-dark"><?php echo $row['mother_tongue'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('religion')?></label>
                                                                <p class="text-dark"><?php echo $row['religion'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('caste')?></label>
                                                                <p class="text-dark"><?php echo $row['caste'] ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                

                                                    <div class="row">
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('mobile_no')?></label>
                                                                <p class="text-dark"><?php echo $row['mobileno'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('email')?></label>
                                                                <p class="text-dark"><?php echo $row['studentMail'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('city')?></label>
                                                                <p class="text-dark"><?php echo $row['city'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('state')?></label>
                                                                <p class="text-dark"><?php echo $row['state'] ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('present_address')?></label>
                                                                <p class="text-dark"><?php echo $row['current_address'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('permanent_address')?></label>
                                                                <p class="text-dark"><?php echo $row['permanent_address'] ?></p>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="headers-line mt-md">
                                                        <i class="fas fa-user-check"></i> <?=translate('Detalles Padre')?>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('Nombre')?> <span class="required">*</span></label>
                                                                <p class="text-dark"><?php echo $row['parent_name'] ?></p>
                                                            </div>
                                                        </div>
                                                      
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('Relacion')?></label>
                                                                <p class="text-dark"><?php echo $row['parent_relation'] ?></p>

                                                                
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('Correo')?></label>
                                                                <p class="text-dark"><?php echo $row['parent_email'] ?></p>

                                                                
                                                            </div>
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-3 mb-sm">
                                                            <div class="form-group">
                                                                <label class="control-label"><?=translate('Telefono')?></label>
                                                                <p class="text-dark"><?php echo $row['parent_mobileno'] ?></p>
                                                            </div>
                                                        
                                                    </div>

                                                </div>


                                                    

                                                    

                                            </div>
                                            
                                        
                                        </div>
                                    </div>
                                </div>

                                <td>
                                    <?php if ($row['resultadoEnviado'] == 1) : ?>
                                        Enviados
                                        <div class="card">
                                            <div class="card-body">
                                                <?php if (pathinfo('ResultadosPsicologico_'.$row['first_name'].'_'.$row['last_name'].'.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                                                    <a href="<?php echo base_url(str_replace(' ', '_','Student/verPDF/ResultadosAcademicos_'.$row['first_name'].'_'.$row['last_name'].''.'?parametro='. $row['idEstudiante'])); ?>" target="_blank" class="btn btn-primary">
                                                        <i class="fas fa-file-pdf"></i> Ver resultados Psicológicos
                                                    </a>
                                                <?php else : ?>
                                                    <span>Tipo de archivo no compatible.</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        No enviados
                                    <?php endif; ?>
                                </td>


                            
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
              
        </div>

    </div>


