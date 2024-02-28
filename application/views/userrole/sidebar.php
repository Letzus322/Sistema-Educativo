<aside id="sidebar-left" class="sidebar-left">
	<div class="sidebar-header">
		<div class="sidebar-title">
			Main
		</div>
	</div>

	<div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
<?php
$isNewStudent= false;
$userId = get_loggedin_user_id();

$this->db->select('*');
$this->db->from('estadoMatricula');
$this->db->where('idEstudiante', $userId);
$query = $this->db->get();
if ($query->num_rows() > 0) {
    // Si encuentra al menos un registro con uno de los campos igual a 0, o si no hay registro, retorna True
    $this->db->select('*');
    $this->db->from('estadoMatricula');
    $this->db->where('idEstudiante', $userId);
    $this->db->where('(subidaArchivos = 0 OR citaAcademica = 0 OR citaPsicologica = 0 OR  contratoFirmado = 0  OR contratoValidado = 0)');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        // Si encuentra al menos un registro con uno de los campos igual a 0, retorna True
        $isNewStudent= true;
    } else {
        // Si todos los campos son 1 (no se encontró ninguno con 0), retorna False
        $isNewStudent = false;
    }



} else {
    // Si no encuentra ningún registro para ese idEstudiante y todos los campos están en 1, retorna False
    $isNewStudent= true;
}


if ($isNewStudent && is_student_loggedin()){
    $this->session->set_userdata('set_session_id', 6);
    ?>             
                    
                    <!-- dashboard -->

                

                    <li class="nav-parent <?php if ($main_menu == 'archivos') echo 'nav-expanded nav-active'; ?>">
                        <a>
                            <i class="icons icon-home" aria-hidden="true"></i><span><?=translate('Expediente de postulante').$documentosNoSubidos?></span>
                        </a>
                        <ul class="nav nav-children">
                            <!-- subject -->
                            <li class="<?php if ($sub_page == 'archivos/subirArchivo') echo 'nav-active'; ?>">
                                <a href="<?=base_url('archivos/subirArchivo')?>">
                                    <i class="fas fa-book-reader"></i><?=translate('Subir Archivos')?>
                                </a>
                            </li>
							
							<!-- class schedule -->
							<li class="<?php if ($sub_page == 'archivos/verArchivo') echo 'nav-active'; ?> ">
								<a href="<?=base_url('archivos/verArchivo')?>">
									<i class="fas fa-dna"></i><span><?=translate('Ver') . " " . translate('Archivos')?></span>
								</a>
							</li>

                          
                        </ul>
                    </li>

                    <li class="nav-parent <?php if ($main_menu == 'citas') echo 'nav-expanded nav-active'; ?>">
                        <a>
                            <i class="icons icon-home" aria-hidden="true"></i><span><?=translate('Citas')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <!-- subject -->
                            <li class="<?php if ($sub_page == 'citas/citaPsicologica') echo 'nav-active'; ?>">
                                <a href="<?=base_url('citas/citaPsicologica')?>">
                                    <i class="fas fa-book-reader"></i><?=translate('Cita Psicológica')?>
                                </a>
                            </li>
							
							<!-- class schedule -->
							<li class="<?php if ($sub_page == 'citas/citaAcademica') echo 'nav-active'; ?> ">
								<a href="<?=base_url('citas/citaAcademica')?>">
									<i class="fas fa-dna"></i><span><?=translate('Cita') . " " . translate('Académica')?></span>
								</a>
							</li>

                            <li class="<?php if ($sub_page == 'citas/resultadoCitas') echo 'nav-active'; ?> ">
								<a href="<?=base_url('citas/resultadoCitas')?>">
                                <i class="fas fa-chart-bar"></i><span><?=translate('Resultados Citas')?></span>
								</a>
							</li>
                        </ul>
                    </li>


                  

                    <li class="nav-parent <?php if ($main_menu == 'subirContrato') echo 'nav-expanded nav-active'; ?>">
                        <a href="<?= base_url('citas/subirContrato') ?>">
                            <i class="fas fa-file-signature"></i><span><?= translate('Enviar Contrato') ?></span>
                        </a>
                    </li>

                    <li class="nav-parent <?php if ($main_menu == 'estadoCitas') echo 'nav-expanded nav-active'; ?>">
                        <a href="<?= base_url('citas/estadoCitas') ?>">
                            <i class="far fa-calendar-check"></i><span><?= translate('Ver estado de Registro') ?></span>
                        </a>
                    </li>

                    <!-- fees history -->
                    <li class="<?php if ($main_menu == 'fees') echo 'nav-active';?> ">
                        <a href="<?=base_url('userrole/invoice')?>">
                            <i class="icons icon-calculator"></i><span><?=translate('Historial de pagos')?></span>
                        </a>
                    </li>


<?php
} 
else {
if (is_student_loggedin()) {
    ?>             
                    
                    <!-- dashboard -->

                    <li class="<?php if ($sub_page == 'userrole/dashboard') echo 'nav-active'; ?>">
                        <a href="<?=base_url('dashboard')?>">
                            <i class="icons icon-grid"></i><span><?=translate('dashboard').$documentosNoSubidos?></span>
                        </a>
                    </li>
                    <li class="<?php if ($main_menu == 'reservation') echo 'nav-active'; ?>">
                        <a href="<?=base_url('reservation/index?hijoId='. $this->session->	userdata('myChildren_id') )?>">
                            <i class="far fa-suitcase"></i><span><?=translate('RESERVA DE VACANTE')?></span>
                        </a>
                    </li>

                    <!-- Matriculas -->
                    <li class="nav-parent <?php if ($main_menu == 'matriculas') echo 'nav-expanded nav-active'; ?>">
                        <a>
                            <i class="fas fa-graduation-cap" aria-hidden="true"></i><span><?=translate('Matriculas')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <!-- 1ra funcion matriculas -->
                            <li class="<?php if ($sub_page == 'citas/subirContratoAntiguos') echo 'nav-active'; ?>">
                                <a href="<?=base_url('student/subirContratoAntiguos')?>">
                                    <i class="far fa-file-alt"></i><?=translate('Envio de contrato')?>
                                </a>
                            </li>
                        </ul>
                    </li>


                    
<?php } elseif (is_parent_loggedin()) {  ?>

                    <li class="nav-parent <?php if ($main_menu == 'dashboard') echo 'nav-expanded nav-active'; ?>">
                        <a>
                            <i class="icons icon-grid"></i><span><?=translate('dashboard')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'userrole/dashboard' && empty(get_activeChildren_id())) echo 'nav-active'; ?>">
                                <a href="<?=base_url('parents/my_children')?>">
                                    <i class="fab fa-slideshare"></i><span><?=translate('my_children')?></span>
                                </a>
                            </li>
                            <?php if (!empty(get_activeChildren_id())): ?>
                                <li class="<?php if ($sub_page == 'userrole/dashboard') echo 'nav-active'; ?>">
                                    <a href="<?=base_url('dashboard'); ?>">
                                        <i class="fas fa-tachometer-alt"></i><span><?=translate('dashboard')?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
<?php } if (is_student_loggedin()) { ?>



                    
                    <!-- student profile -->
                    
                    <li class="<?php if ($main_menu == 'profile') echo 'nav-active'; ?>">
                        <a href="<?=base_url('profile')?>">
                            <i class="far fa-user-circle"></i><span><?=translate('profile')?></span>
                        </a>
                    </li>
<?php
}
if ((is_parent_loggedin() && !empty(get_activeChildren_id())) || is_student_loggedin()) {
    ?>             

                 

                    <!-- teachers -->
                    <li class="<?php if ($main_menu == 'teachers') echo 'nav-active'; ?>">
                        <a href="<?=base_url('userrole/teachers')?>">
                            <i class="icon-people icons"></i><span><?=translate('teachers')?></span>
                        </a>
                    </li>

                    <!-- academic -->
                    <li class="nav-parent <?php if ($main_menu == 'academic') echo 'nav-expanded nav-active'; ?>">
                        <a>
                            <i class="icons icon-home" aria-hidden="true"></i><span><?=translate('academic')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <!-- subject -->
                            <li class="<?php if ($sub_page == 'userrole/subject') echo 'nav-active'; ?>">
                                <a href="<?=base_url('userrole/subject')?>">
                                    <i class="fas fa-book-reader"></i><?=translate('subject')?>
                                </a>
                            </li>
							
							<!-- class schedule -->
							<li class="<?php if ($sub_page == 'userrole/class_schedule') echo 'nav-active'; ?> ">
								<a href="<?=base_url('userrole/class_schedule')?>">
									<i class="fas fa-dna"></i><span><?=translate('class') . " " . translate('schedule')?></span>
								</a>
							</li>


                            <li class="<?php if ($sub_page == 'solucionarios/vista_estudiante') echo 'nav-active'; ?> ">
								<a href="<?=base_url('solucionarios/vista_estudiante')?>">
									<i class="fas fa-dna"></i><span>Solucionarios</span>
								</a>
							</li>
                        </ul>
                        
                    </li>


<?php if (is_student_loggedin()) { ?>
                    <li class="<?php if ($main_menu == 'live_class') echo 'nav-active';?>">
                        <a href="<?=base_url('userrole/live_class')?>">
                            <i class="icons icon-earphones-alt"></i><span><?=translate('live_class_rooms')?></span>
                        </a>
                    </li>
<?php } ?>
                    <!-- leave -->
                    <li class="<?php if ($main_menu == 'leave') echo 'nav-active'; ?>">
                        <a href="<?=base_url('userrole/leave_request')?>">
                            <i class="icons icon-docs"></i><span><?=translate('leave_application')?></span>
                        </a>
                    </li>

                    <!-- attachments upload -->
                    <li class="<?php if ($main_menu == 'attachments') echo 'nav-active'; ?> ">
                        <a href="<?=base_url('userrole/attachments')?>">
                            <i class="icons icon-cloud-upload"></i><span><?=translate('attachments_book')?></span>
                        </a>
                    </li>
                    
                    <!-- homework -->
                    <li class="<?php if ($main_menu == 'homework') echo 'nav-active'; ?> ">
                        <a href="<?=base_url('userrole/homework')?>">
                            <i class="icons icon-note"></i><span><?=translate('homework')?></span>
                        </a>
                    </li>

                    <!-- exam master -->
                    <li class="nav-parent <?php if ($main_menu == 'exam') echo 'nav-expanded nav-active'; ?>">
                            <a>
                            <i class="icons icon-book-open" aria-hidden="true"></i><span><?=translate('exam_master')?></span>
                        </a>
                        <ul class="nav nav-children">
							<!-- exam schedule -->
							<li class="<?php if ($sub_page == 'userrole/exam_schedule') echo 'nav-active'; ?> ">
								<a href="<?=base_url('userrole/exam_schedule')?>">
									<i class="fas fa-dna"></i><span><?=translate('exam') . " " . translate('schedule')?></span>
								</a>
							</li>
					
                            <!-- marks -->
                            <li class="<?php if ($sub_page == 'userrole/report_card') echo 'nav-active'; ?>">
                                <a href="<?=base_url('userrole/report_card')?>">
                                    <i class="fas fa-marker"></i><span><?=translate('report_card')?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
<?php if (is_student_loggedin()) { ?>
                    <!-- online exam master -->
                    <li class="<?php if ($main_menu == 'onlineexam') echo ' nav-active'; ?>">
                        <a href="<?=base_url('userrole/online_exam')?>">
                            <i class="icon-screen-desktop"></i><span><?=translate('online_exam')?></span>
                        </a>
                    </li>
<?php } ?>
                    <!-- supervision -->
                    <li class="nav-parent <?php if ($main_menu == 'supervision')  echo 'nav-expanded nav-active'; ?>">
                        <a>
                            <i class="icons icon-feed" aria-hidden="true"></i><span><?=translate('supervision')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <!-- hostels -->
                            <li class="<?php if ($sub_page == 'userrole/hostels') echo 'nav-active';?>">
                                <a href="<?=base_url('userrole/hostels')?>">
                                    <i class="fas fa-store-alt"></i><span><?=translate('hostel')?></span>
                                </a>
                            </li>

                            <!-- transport -->
                            <li class="<?php if ($sub_page == 'userrole/transport_route') echo 'nav-active'; ?>">
                                <a href="<?=base_url('userrole/route')?>">
                                    <i class="fas fa-bus"></i><span><?=translate('transport')?></span>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <!-- attendance control -->
                    <li class="<?php if ($main_menu == 'attendance') echo ' nav-active'; ?>">
                        <a href="<?=base_url('userrole/attendance')?>">
                            <i class="icons icon-chart"></i><span><?=translate('attendance')?></span>
                        </a>
                    </li>

                    <li class="nav-parent <?php if ($main_menu == 'library') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-notebook"></i><span><?=translate('library')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'userrole/book') echo 'nav-active';?>">
                                <a href="<?=base_url('userrole/book')?>">
                                    <span><i class="fas fa-caret-right"></i><?=translate('books') . " " . translate('list')?></span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'userrole/book_request') echo 'nav-active';?>">
                                <a href="<?=base_url('userrole/book_request')?>">
                                    <span><i class="fas fa-caret-right"></i>Issued Book</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- events -->
                    <li class="<?php if ($sub_page == 'userrole/event') echo 'nav-active'; ?> ">
                        <a href="<?=base_url('userrole/event')?>">
                            <i class="icons icon-speech"></i><span><?=translate('events')?></span>
                        </a>
                    </li>

                   <!-- fees history -->
                    <li class="<?php if ($main_menu == 'fees') echo 'nav-active';?> ">
                        <a href="<?=base_url('userrole/invoice')?>">
                            <i class="icons icon-calculator"></i><span><?=translate('fees_history')?></span>
                        </a>
                    </li>
                   
                    <!-- message -->
                    <li class="<?php if ($main_menu == 'message') echo 'nav-active'; ?> ">
                        <a href="<?=base_url('communication/mailbox/inbox')?>">
                            <i class="icons icon-envelope-open"></i><span><?=translate('message')?></span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
<?php }  }?>
		<script>
			// maintain scroll position
			if (typeof localStorage !== 'undefined') {
				if (localStorage.getItem('sidebar-left-position') !== null) {
					var initialPosition = localStorage.getItem('sidebar-left-position'),
						sidebarLeft = document.querySelector('#sidebar-left .nano-content');
					sidebarLeft.scrollTop = initialPosition;
				}
			}
		</script>
	</div>
</aside>
<!-- end sidebar -->