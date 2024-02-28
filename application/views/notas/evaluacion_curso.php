<section class="panel">
	<div class="tabs-custom">

		<div class="tab-content">
			<div class="tab-pane active">
				<div class="row">

					
						<section class="panel panel-custom">
							<header class="panel-heading panel-heading-custom">
								<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?=translate('class_list')?></h4>
							</header>

                            
							<div class="panel-body panel-body-custom">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-condensed tbr-top mb-none">
										<thead>
											<tr>
												<th><?=translate('Area')?></th>
												<th><?=translate('Curso')?></th>
												<th><?=translate('action')?></th>
											</tr>
										</thead>
										<tbody>
                                                <?php
                                                $count = 1;
                                                if (count($classlist)){
                                                    foreach($areas as $row):
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['name'];?></td>
                                                    <td></td>

                                                    <td>
                                                    </td>


                                                    <?php  

                                                    $this->db->select('subject.*, subject_assign.id as curso_asignado');
                                                    $this->db->from('subject_assign');
                                                    $this->db->join('sections_allocation', 'subject_assign.class_id = sections_allocation.class_id AND subject_assign.section_id = sections_allocation.section_id');
                                                    $this->db->join('subject', 'subject.id = subject_assign.subject_id');
                                                    $this->db->join('area', 'subject.area_id = area.id');
                                                    $this->db->where('sections_allocation.id', $row['sections_allocation_id']);
                                                    $this->db->where('area.id', $row['id']);
                                                    $this->db->where('subject_assign.session_id',get_session_id());

                                                    $query = $this->db->get();
                                                    $result = $query->result_array();

                                                  
                                                    ?>

                                                    <?php foreach ($result as $curso): 
                                                          $this->db->select('*');
                                                          $this->db->from('subject_assign_competencias');
                                                          $this->db->where('subject_assign_competencias.subject_assign_id', $curso['curso_asignado']);
                                                          $competencias_query = $this->db->get();
                                                          $numero_de_registros = $competencias_query->num_rows();

                                                        
                                                        ?>
                                                        <tr>


                                                            <td> </td>
                                                            <td><?php echo $curso['name']; ?></td>
                                                            <td>
                                                            <?php if ($numero_de_registros === 0): ?>
                                                                <!-- Botón para crear cuando $numero_de_registros es 0 -->
                                                                <a href="<?php echo base_url('notas/evaluacion_curso_crear/' . $curso['curso_asignado'].'/1');?>" class="btn btn-default btn-circle icon">
                                                                    <i class="fas fa-pen-nib"> Crear</i>
                                                                </a>
                                                            <?php else: ?>
                                                                <!-- Botones para editar y copiar cuando $numero_de_registros es mayor a 0 -->
                                                              

                                                                <select  onchange="redirectPage(this)">
                                                                <option value="">Seleccionar</option>

                                                                    <option value="<?php echo base_url('notas/evaluacion_curso_crear/' . $curso['curso_asignado'] . '/1'); ?>">Bimestre I</option>
                                                                    <option value="<?php echo base_url('notas/evaluacion_curso_crear/' . $curso['curso_asignado'] . '/2'); ?>">Bimestre II</option>
                                                                    <option value="<?php echo base_url('notas/evaluacion_curso_crear/' . $curso['curso_asignado'] . '/3'); ?>">Bimestre III</option>
                                                                    <option value="<?php echo base_url('notas/evaluacion_curso_crear/' . $curso['curso_asignado'] . '/4'); ?>">Bimestre IV</option>
                                                                </select>

                                                                <a href="<?php echo base_url('notas/copiar/' . $curso['curso_asignado']);?>" class="btn btn-default btn-circle icon">
                                                                    <i class="fas fa-pen-nib"> Copiar</i>
                                                                </a>
                                                            <?php endif; ?>


                                                                
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>




                                                    
                                                </tr>


                                            <?php
                                                endforeach;
                                            }else{
                                                echo '<tr><td colspan="6"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
                                            }
										?>
										</tbody>
									</table>


								</div>
							</div>
						</section>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
    function redirectPage(select) {
        var url = select.value;
        window.location.href = url;
    }
</script>   