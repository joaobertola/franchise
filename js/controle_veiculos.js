$(document).ready(function () {
  //Initialize Select2 Elements
  $(".select2").select2();

  //Initialize Select2 Elements
  $(".select2bs4").select2({
    theme: "bootstrap4",
  });

  $("#veiculosDataTable").DataTable({
    responsive: true,
    autoWidth: true,
  });

  $(".datePicker").datepicker({
    format: "dd/mm/yyyy",
  });

  $("#ano_modelo").datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
  });
});

$(document).on("click", "#insertVeiculo", function (e) {
  e.preventDefault();
  var dados = new FormData($("form[name='cadastroVeiculo']")[0]);
  var valida = validaForm({
    form: $("form[name='cadastroVeiculo']"),
    notValidate: true,
    validate: true,
  });

  if (valida) {
    $.ajax({
      type: "POST",
      url: "../php/veiculos/json/jsonVeiculos.php",
      data: dados,
      dataType: "json",
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.status) {
          $("#sucessoAlert").delay(800).css("display", "block");
          $("form[name='cadastroVeiculo']")[0].reset();
          window.location.reload();
        } else {
          $("#erroAlert").css("display", "block");
        }
      },
    });
  }
});

$(document).on("click", "#sendCadastroOcorrencia", function (e) {
  e.preventDefault();
  var dados = new FormData($("form[name='cadastroOcorrencia']")[0]);
  var valida = validaForm({
    form: $("form[name='cadastroOcorrencia']"),
    notValidate: true,
    validate: true,
  });

  if (valida) {
    $.ajax({
      type: "POST",
      url: "../php/veiculos/json/jsonVeiculos.php",
      data: dados,
      dataType: "json",
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.status) {
          $("#sucessoOcorrenciaAlert").delay(800).css("display", "block");
          $("form[name='cadastroOcorrencia']")[0].reset();
          window.location.reload();
        } else {
          $("#erroOcorrenciaAlert").css("display", "block");
        }
      },
    });
  }
});

$(document).on("click", ".editVeiculo", function (e) {
  e.preventDefault();
  var idVeiculo = $(this).data("id");
  $.ajax({
    type: "POST",
    url: "../php/veiculos/json/jsonVeiculos.php?op=listaVeiculo",
    data: {
      idVeiculo: idVeiculo,
    },
    dataType: "json",
    success: function (response) {
      var veiculo = response[0];
      $("#titleEditVeiculo").html(
        "Veículo " + veiculo.idveiculo + " - " + veiculo.modelo
      );
      // Modal lista de ocorrencias
      $("#editModelo").html(veiculo.modelo);
      $("#editPlaca").html(veiculo.placa);
      $("#editAno_modelo").html(veiculo.ano_modelo);
      $("#editCondutor").html(veiculo.condutor);
      $("#editCondutorProvisorio").html(veiculo.condutorprovisorio);
      // Modal Nova Ocorrencia
      $("#ocorrenciaModelo").html(veiculo.modelo);
      $("#ocorrenciaIdVeiculo").val(veiculo.idveiculo);
      $("#ocorrenciaPlaca").html(veiculo.placa);
      $("#ocorrenciaAno_modelo").html(veiculo.ano_modelo);
      $("#ocorrenciaCondutor").val(veiculo.condutor);
      $("#ocorrenciaCondutorProvisorio").val(veiculo.condutorprovisorio);
      // Chamando a lista de Ocorrencias do Veiculo
      $.ajax({
        type: "POST",
        url: "../php/veiculos/view/layout/table_ocorrencias.php",
        data: {
          idVeiculo: idVeiculo,
        },
        dataType: "html",
        success: function (response) {
          $(".registroOcorrencias").html(response);
        },
      });
    },
  });
});

$(document).on("change", "#ocorrencia", function (e) {
  e.preventDefault();
  var ocorrencia = $(this).val();
  $("#inputCondutorProvisorio").addClass("d-none");
  $("#inputChaveReserva").addClass("d-none");
  $("#inputImagemOS").addClass("d-none");
  if (ocorrencia >= 1 && ocorrencia <= 4) {
    $("#inputCondutorProvisorio").removeClass("d-none");
  } else if (ocorrencia == 5) {
    $("#inputChaveReserva").removeClass("d-none");
  } else if (ocorrencia == 6) {
    $("#inputImagemOS").removeClass("d-none");
  }
});

$(document).on("click", ".openImagem", function (e) {
  e.preventDefault();
  var imagem = $(this).data("imagem");
  var idOcorrencia = $(this).data("id");
  $("#imagem" + idOcorrencia).attr("src", imagem);
  $("#linhaImagem" + idOcorrencia).css("display", "block");
});

$(document).on("click", ".excluiVeiculo", function(e){
  e.preventDefault();
  var idVeiculo = $(this).data("id");
  $.ajax({
    type: "POST",
    url: "../php/veiculos/json/jsonVeiculos.php?op=deleteVeiculo",
    data: {
      idVeiculo : idVeiculo
    },
    dataType: "json",
    success: function (response) {
      if (response.status) {
        window.location.reload();
      }
    }
  });
});

/**
 *
 * @param {Inputs dos Formulários} params
 * @returns {true or false}
 */

function validaForm(params) {
  var valida = true;
  var notpermitidos = ["", "__/__/____", undefined, null];
  var config = {
    form: $("form"),
    notValidate: false,
  };
  $.extend(config, params);

  var $form = config.form;

  $form.find(".form-required").each(function () {
    var border = !$(this).val() ? "1px solid red" : "1px solid #cecece";

    if ($.inArray($(this).val(), notpermitidos) == 0) valida = false;

    $(this).closest("input, textarea, select").css("border", border);
  });

  return valida;
}
