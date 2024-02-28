<style>

.table-responsive {
    overflow: visible; /* o auto, según tus necesidades */
}


</style>



<section class="panel">
	<div class="tabs-custom">

		<div class="tab-content">
			<div class="tab-pane active">



            

            
				<div class="row">

                        <?php $subject_assign_id = $curso_asignado['id_subject_assign'];
                        $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia

                        $query = $this->db->get_where('subject_assign_competencias', array(
                            'subject_assign_id' => $subject_assign_id,
                            'bimestre' => $bimestre
                        ));

                        $result = $query->result_array();
                        $numResultados = count($result);



                        $this->db->select('*');
                        $this->db->from('ewgcgdaj_instituto.envio_notas_bimestre');
                        $this->db->where('envio_notas_bimestre.id_subject_assign', $subject_assign_id);
                        $this->db->where('envio_notas_bimestre.bimestre',$bimestre );
                        $query = $this->db->get();
                        
                        if ($query->num_rows() > 0) {
                            $notas_enviadas = "ENVIADAS"; // Al menos un registro encontrado
                        } else {
                            $notas_enviadas = "NO ENVIADAS"; // Ningún registro encontrado
                        }
                        

                        ?>



						<section class="panel panel-custom">
							<header class="panel-heading panel-heading-custom">
								<h4 class="panel-title">Periodo:</h4>
                                <h4 class="panel-title">Curso: <?php echo $curso_asignado['curso'];?></h4>

                                <h4 class="panel-title">Ingreso de Notas: Numeros</h4>
                                <h4 class="panel-title">Estado de notas:    <?php echo $notas_enviadas;?> </h4>


							</header>



                            <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Example</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .hide {
            display: none;
        }
    </style>
</head>
<body>


<?php

$this->db->select('*');
$this->db->from('ewgcgdaj_instituto.periodo_bimestre');
$this->db->where('id_bimestre', $bimestre);
$this->db->where('id_session', get_session_id());

$query = $this->db->get();
$fechaBimestre = $query->row_array();



$this->db->select('*');
$this->db->from('ewgcgdaj_instituto.prorroga_fecha');
$this->db->where('prorroga_fecha.subject_assignId', $subject_assign_id);
$this->db->where('prorroga_fecha.bimestre',$bimestre );

$query = $this->db->get();
$fechaBimestreAplazada = $query->row_array();
if (empty($fechaBimestreAplazada)) {
    // Asignar los valores de $fechaBimestre a $fechaBimestreAplazada
    $fechaBimestreAplazada = $fechaBimestre;
}
$fechaMayor = max($fechaBimestreAplazada['nueva_fecha_cierre'], $fechaBimestre['fecha_fin_notas']);



?>








<table>
    <thead>
        <tr>

            <th rowspan="4"  style="width: 400px;">Alumno</th>
            <th rowspan="4">Promedio Final</th>
            <th rowspan="4">Promedio Literal</th>

            <?php  $arregloCapacidadesNumero=[];
               

                $arregloCapacidadesNumero2=[];
                foreach ($result as $row): ?>

                <?php

                $this->db->select('*');

                $this->db->from('subject_assign_competencias');
                $this->db->join('subject_assign_capacidades', 'subject_assign_competencias.id = subject_assign_capacidades.competencia_assign_id');
                
                $this->db->where('subject_assign_competencias.subject_assign_id', $subject_assign_id);
                $this->db->where('subject_assign_competencias.bimestre', $bimestre);
                $this->db->where('subject_assign_competencias.id', $row['id']);

                $query = $this->db->get();
                $arregloCapacidades = $query->result_array();
                $numeroCapacidades = count($arregloCapacidades);
                $arregloCapacidadesNumero[] = $numeroCapacidades;


        


                
                $this->db->select('*');

                $this->db->from('subject_assign_competencias');
                $this->db->join('subject_assign_capacidades', 'subject_assign_competencias.id = subject_assign_capacidades.competencia_assign_id');
                $this->db->join('subject_assign_indicadores', 'subject_assign_capacidades.id = subject_assign_indicadores.capacidad_assign_id');
                
                $this->db->where('subject_assign_competencias.subject_assign_id', $subject_assign_id);
                $this->db->where('subject_assign_competencias.bimestre', $bimestre);
                $this->db->where('subject_assign_competencias.id', $row['id']);

                $query = $this->db->get();

                
                $result2 = $query->result_array();
                $numResultados2 = count($result2);

                ?>
            <th colspan="<?= $numResultados2 + $numeroCapacidades + 1?>" style="text-align: center; background-color: <?= $row['color'] ?>;"><?= $row['name'] ?></th>


            <?php
            ?>

            <?php endforeach; ?>

           

         
            <!-- Add more competencias as needed -->
        </tr>

        <tr>

            <?php 

                $subject_assign_id = $curso_asignado['id_subject_assign'];


                    $this->db->select('*');
                    $this->db->from('subject_assign_competencias');
                    $this->db->join('subject_assign_capacidades', 'subject_assign_competencias.id = subject_assign_capacidades.competencia_assign_id');
                    $this->db->where('subject_assign_competencias.subject_assign_id', $subject_assign_id);
                    $this->db->where('subject_assign_competencias.bimestre', $bimestre);
                    $this->db->order_by('subject_assign_competencias.id', 'ASC'); // Cambia 'ASC' a 'DESC' si deseas orden descendente

                    $query3 = $this->db->get();

                    
                    $result3 = $query3->result_array();
                    $numResultados3 = count($result3);



                   

            ?>

            <?php 
            $arreglo=[];
            $i = 1;
            $j= 0;   
            foreach ($result3 as $row3): ?>








                <?php
                        $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia

                        $query5 = $this->db->get_where('subject_assign_indicadores', array(
                            'capacidad_assign_id' => $row3['id']));

                        $result5 = $query5->result_array();
                        $numResultados5 = count($result5);
                        $arreglo[] = $numResultados5;
                        ?>
                <th colspan="<?= $numResultados5 + 1?>" style="text-align: center; background-color: <?= $row3['color'] ?>;"><?= $row3['name'] ?></th>

                <?php
                ?>

                    <?php if ($arregloCapacidadesNumero[$j]==$i): ?>
                        <th  rowspan="3" style="text-align: center;"> Nivel de logro</th>
                       

                        

                    <?php 
                    $i = 0;
                    $j= $j+1;
                    endif; ?>
                
            <?php         
            $i = $i+1;
            endforeach; ?>


        <tr>

        <tr>

        <?php $subject_assign_id = $curso_asignado['id_subject_assign'];


                $this->db->select('*');
                $this->db->from('subject_assign_competencias');
                $this->db->join('subject_assign_capacidades', 'subject_assign_competencias.id = subject_assign_capacidades.competencia_assign_id');
                $this->db->join('subject_assign_indicadores', 'subject_assign_capacidades.id = subject_assign_indicadores.capacidad_assign_id');
                
                $this->db->where('subject_assign_competencias.subject_assign_id', $subject_assign_id);
                $this->db->where('subject_assign_competencias.bimestre', $bimestre);
                $query4 = $this->db->get();

                
                $result4 = $query4->result_array();
                $numResultados4 = count($result4);

        ?>

        <?php
        $i = 1;
        $j= 0;
        foreach ($result4 as $row4): ?>
            <th colspan="1"  style="text-align: center; background-color: <?= $row4['color'] ?>;"><?= $row4['name'] ?></th>
            

            <?php if ($arreglo[$j]==$i): ?>
                <th colspan="1">Prom</th>
              

            <?php 
            $i = 0;
            $j= $j+1;
            endif; ?>

        <?php        
        $i = $i+1;
        endforeach; ?>


        <tr>
        
    </thead>
    
    <tbody>

       

        <?php 
        foreach ($alumnos as $alumno): ?>

        <tr>
            
        <td><?= mb_strtoupper($alumno['apellido'], 'UTF-8').' '.mb_strtoupper($alumno['nombre'], 'UTF-8') ?></td>


        <td colspan="1" style="padding: 0 border:0">
            <input type="number" id="promedioFinal<?=$alumno['id']?>" name="promedio" step="any" style="width: 100%; height: 100%; padding: 4px; border: none; box-sizing: border-box;" readonly>
        </td>

        <td colspan="1" style="padding: 0 border:0">
            <input type="text"id="promedioLiteral<?=$alumno['id']?>" name="promedioLiteral" step="any" style="width: 100%; height: 100%; padding: 4px; border: none; box-sizing: border-box;" readonly>
        </td>


        


        <?php $subject_assign_id = $curso_asignado['id_subject_assign'];
                        $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia

                        $query = $this->db->get_where('subject_assign_competencias', array(
                            'subject_assign_id' => $subject_assign_id,
                            'bimestre' => $bimestre
                        ));

                        $competencias = $query->result_array();
                        $numCompetencias = count($competencias);?>


            <?php
            $contador=0;
            $sumaCompetencias=0;

            foreach ($competencias as $competencia): ?>

                        
                <?php $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia

                $query = $this->db->get_where('subject_assign_capacidades', array(
                    'competencia_assign_id' => $competencia['id'],
                    
                ));

                $capacidades = $query->result_array();
                $numCapacidades = count($capacidades);?>

            <?php
                $sumaCapacidades=0;

                foreach ($capacidades as $capacidad): ?>

                <?php $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia

                $query = $this->db->get_where('subject_assign_indicadores', array(
                    'capacidad_assign_id' => $capacidad['id'],
                    
                ));

                $indicadores = $query->result_array();
                $numIndicadores = count($indicadores);?>

                    <?php 
                    
                    $sumaIndicadores=0;

                    foreach ($indicadores as $indicador): ?>

                        
                        <input type="number" id="indicadorValor-<?=$contador?>-<?=$alumno['id']?>" value="<?=$indicador['id']?>" name="promedio" step="any" style="visibility: hidden;" hidden>

                        <?php 
                        
                        $this->db->select('nota');
                        $this->db->where('id_alumno', $alumno['id']);
                        $this->db->where('id_indicador', $indicador['id']);
                        $registronota = $this->db->get('notas_alumnos');
                        $notaAlumno = $registronota->row()->nota;
                        ?>
                        <td colspan="1" style="padding: 0 border:0">
                            <input type="number" id="indicador-<?=$contador?>-<?=$alumno['id']?>" value="<?=$notaAlumno?>"  name="promedio" step="any" style="width: 100%; height: 100%; padding: 4px; border: 1px solid #ccc; box-sizing: border-box;">
                        </td>
            



                    <?php 
                    $sumaIndicadores=$notaAlumno*$indicador['peso']/100+$sumaIndicadores;

                    $contador=$contador+1; 
                    endforeach;
                    $sumaCapacidades=$sumaIndicadores*$capacidad['peso']+$sumaCapacidades;

                    ?>
                    <td colspan="1" ><?=$sumaIndicadores?></td>

            <?php endforeach;
            $sumaCompetencias=($sumaCapacidades/$numCapacidades)*$competencia['peso']+$sumaCompetencias;

            
            ?>

            <td colspan="1" ><?=$sumaCapacidades/$numCapacidades?></td>


        <?php endforeach; ?>

        <script>
        var input = document.getElementById("promedioFinal<?= $alumno['id'] ?>");
        input.value = Math.round(<?= $sumaCompetencias/$numCompetencias ?>); 

        
        var inputPromedioLiteral = document.getElementById("promedioLiteral<?= $alumno['id'] ?>");

        // Determinas el promedio literal basado en el valor del promedio
        var promedioLiteral;
        if (input.value >= 0 && input.value <= 10) {
            promedioLiteral = "C";
        } else if (input.value >= 11 && input.value <= 13) {
            promedioLiteral = "B";
        } else if (input.value >= 14 && input.value <= 17) {
            promedioLiteral = "A";
        } else if (input.value >= 18 && input.value <= 20) {
            promedioLiteral = "AD";
        } else {
            promedioLiteral = "Valor fuera de rango";
        }

        // Asignas el valor del promedio literal al input
        inputPromedioLiteral.value = promedioLiteral;


        </script>

       


       
        <?php endforeach; ?>
    </tbody>
</table>

    <br>    
</body>

                            
                        <?php if($notas_enviadas =='ENVIADAS'): ?>

                        <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="reactivarEnvioNota" data-toggle="reactivarEnvioNota" >
                            <i class="fas fa-save fa-fw"></i> Reactivar
                        </button>

                        <?php endif; ?>

                      
                
						</section>
				</div>
			</div>
		</div>

	</div>
</section>



<script>


$(document).ready(function () {
        $("#reactivarEnvioNota").on("click", function () {
            // Definir el objeto postData con la información que deseas enviar
            var postData = {
    bimestre: <?php echo $bimestre; ?>,
    id_subject_assign: <?php echo $subject_assign_id; ?> 
};

            
         


            $.ajax({
                type: "POST",
                url: base_url + 'Notas/reactivar_envio_nota',
                data: postData,
                success: function (response) {
                    console.log(response);
                    
                    alert("Se guardó correctamente");
                    location.reload();

                },
                error: function (error) {
                    alert("Error");
                    console.error("Error en la solicitud AJAX:", error);
                }
            });
        });


       
    });
</script>                    