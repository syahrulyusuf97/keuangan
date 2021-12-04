function deleteData(url) {
	showLoading();
	axios.delete(url)
	.then(function (response) {
	  	hideLoading();
	  	if (response.data.status == "success") {
	  		$('.saldo').text("Saldo = "+response.data.sisa_saldo);
	  		$('.sisa_saldo_bank').text(response.data.sisa_saldo_bank);
	  		$('.sisa_saldo_kas').text(response.data.sisa_saldo_kas);
	  		refreshTable();
	  		alertSuccess('Berhasil', response.data.message);
	  	} else {
	  		alertWarning('Gagal', response.data.message);
	  	}
	  })
	  .catch(function (error) {
	  	hideLoading();
	    alertError('Error', error);
	  });
}

function alertSuccess(title, text) {
	Swal.fire({
	  type: 'success',
	  title: title,
	  text: text,
	  timer: 2000
	})
}

function alertError(title, text) {
	Swal.fire({
	  type: 'error',
	  title: title,
	  text: text,
	  timer: 2000
	})
}

function alertWarning(title, text) {
	Swal.fire({
	  type: 'warning',
	  title: title,
	  text: text,
	  timer: 2000
	})
}

function alertConfirm(title, text, url) {
	Swal.fire({
		type: 'question',
	  	title: title,
	  	text: text,
	  	showCancelButton: true,
	  	confirmButtonText: 'Ya',
	  	cancelButtonText: 'Batal',
	}).then((result) => {
	  /* Read more about isConfirmed */
	  if (result.value) {
	    deleteData(url);
	  }
	})
}