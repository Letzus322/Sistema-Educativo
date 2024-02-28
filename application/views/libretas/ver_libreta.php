<section class="panel">
	<div class="tabs-custom">

		<div class="tab-content">
			<div class="tab-pane active">
				<div class="row">

					
						
                           
                <div class="panel-body panel-body-custom">

                    <div class="input-group">
                        <span class="input-group-addon">Año académico:</span>
                        <input type="text" class="form-control" value="<?php echo $informacionSalon->school_year; ?>" readonly>
                        <span class="input-group-addon">Sede:</span>


                        <input type="text" class="form-control" value="<?php echo $informacionSalon->branch; ?>" readonly>

                        <span class="input-group-addon">Sección:</span>

                        <input type="text" class="form-control" value="<?php echo $informacionSalon->class_name .' '.$informacionSalon->section; ?>" readonly>


                    </div>
                    <br>

                   
                  

                   

                    
                       
                </div>
                                       

							<div class="panel-body panel-body-custom">
								<div class="table-responsive">
                                    
                                    <table>
                                        <thead>
                                            <tr>

                                                <th rowspan="4"  style="width: 400px;">AREA</th>
                                                <th rowspan="4">CURSO</th>
                                                <th rowspan="4">COMPETENCIA</th>

                                                

                                            
                                            </tr>

                                        
                                            
                                        </thead>
                                        
                                        <tbody>

                                        

                                        <?php 
                                            foreach ($classlist as $row): ?>
                                                <tr>

                                                

                                                    <th rowspan="1" style="padding: 0 border:0"><?=$row['area']?></th>
                                                    <th rowspan="1" style="padding: 0 border:0"><?=$row['curso']?></th>
                                                    <th rowspan="1" style="padding: 0 border:0"><?=$row['competencia']?></th>

                                                    


                                                    

                                                </tr>
                                                
                                            

                                            <?php endforeach;  ?>

                                        


                                        
                                        </tbody>
                                    </table>
								</div>
							</div>
				</div>
			</div>
		</div>
	</div>
</section>


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







<script>
    function redirectPage(select) {
        var url = select.value;
        window.location.href = url;
    }
</script>   