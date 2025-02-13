<?php include('db_connect.php'); ?>
<style>
	input[type=checkbox] {
		/* Double-sized Checkboxes */
		-ms-transform: scale(1.3);
		/* IE */
		-moz-transform: scale(1.3);
		/* FF */
		-webkit-transform: scale(1.3);
		/* Safari and Chrome */
		-o-transform: scale(1.3);
		/* Opera */
		transform: scale(1.3);
		padding: 10px;
		cursor: pointer;
	}
</style>
<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">

			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Lista de tramites y costos</b>

					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="">Tramite</th>
									<th class="">Costo total</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$course = $conn->query("SELECT * FROM courses  order by course asc ");
								while ($row = $course->fetch_assoc()) :
								?>
									<tr>
										<td>
											<?php echo $row['course'] . "  " . $row['level'] ?>
										</td>
										<td class="text-right">
											<?php echo number_format($row['total_amount'], 2) ?>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>

						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>

</div>
<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset
	}

	img {
		max-width: 100px;
		max-height: 150px;
	}
</style>

<script>
	$(document).ready(function() {
		$('table').dataTable()
	})
	$('#new_course').click(function() {
		uni_modal("Entrada de nuevos cursos y tarifas", "manage_course.php", 'large')

	})

	$('.edit_course').click(function() {
		uni_modal("Administrar la entrada de cursos y tarifas", "manage_course.php?id=" + $(this).attr('data-id'), 'large')

	})
	$('.delete_course').click(function() {
		_conf("¿Deseas eliminar este curso?", "delete_course", [$(this).attr('data-id')])
	})

	function delete_course($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_course',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Datos eliminados con éxito", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>