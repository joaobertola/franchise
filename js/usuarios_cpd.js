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
});

$(document).on("change", "#imagem", function () {
  //ou Id do input
  var fileInput = $(this);
  var maxSize = $(this).data("max-size");
  var extPermitidas = ["jpg", "png", "gif"];
  if (fileInput.get(0).files.length) {
    var fileSize = fileInput.get(0).files[0].size; // in bytes
    console.log(fileSize, maxSize);
    if (fileSize > maxSize) {
      $("#imagem").val("");
    } else if (
      typeof extPermitidas.find(function (ext) {
        return fileInput.val().split(".").pop() == ext;
      }) == "undefined"
    ) {
      $("#imagem").val("");
    } else {
      const file = fileInput[0].files[0];
      const fileReader = new FileReader();
      fileReader.onload = function () {
        $("#imagem-preview").attr("src", fileReader.result);
        $("#imagem-preview").css("display", "block");
      };
      fileReader.readAsDataURL(file);
    }
  }
});

$(document).on("click", "#insertUsuarioCpd", function (e) {
  e.preventDefault();
  var dados = new FormData($("form[name='cadastroUsuarioCpd']")[0]);
  var valida = validaForm({
    form: $("form[name='cadastroUsuarioCpd']"),
    notValidate: true,
    validate: true,
  });

  if (valida) {
    $.ajax({
      type: "POST",
      url: "../php/cpd/json/jsonUsuariosCpd.php",
      data: dados,
      dataType: "json",
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.status) {
          $("#sucessoAlert").delay(800).css("display", "block");
          $("form[name='cadastroUsuarioCpd']")[0].reset();
          window.location.reload();
        } else {
          $("#erroAlert").css("display", "block");
        }
      },
    });
  }
});

$(document).on("click", ".editUsuarioCpd", function (e) {
  e.preventDefault();
  var idUsuarioCpd = $(this).data("id");
  $("#idUsuarioCpd").val(idUsuarioCpd);
  $("#pass").removeClass("form-required");
  $.ajax({
    type: "POST",
    url: "../php/cpd/json/jsonUsuariosCpd.php?op=listaUsuarioCpd",
    data: {
      idUsuarioCpd: idUsuarioCpd,
    },
    dataType: "json",
    success: function (response) {
      console.log(response);
      var usuarioCpd = response[0];
      $("#titleCadastroUsuarioCpd").html(
        "Usuário " + usuarioCpd.id + " - " + usuarioCpd.nome
      );
      // Modal lista de ocorrencias
      $("#nome").val(usuarioCpd.nome);
      $("#funcao").val(usuarioCpd.funcao);
      if (usuarioCpd.imagem != null && usuarioCpd.imagem != "") {
        $("#imagem-preview").attr(
          "src",
          "../php/cpd/imagens/" + usuarioCpd.imagem
        );
        $("#imagem-preview").css("display", "block");
      }
      if (usuarioCpd.ativo == 1) {
        $("#ativo").attr("checked", "checked");
      } else {
        $("#inativo").attr("checked", "checked");
      }
      $("#user").val(usuarioCpd.user);
    },
  });
});

function resetModal() {
  $("#titleCadastroUsuarioCpd").html("Cadastrar Usuário CPD");
  $("#imagem-preview").attr("src", "/");
  $("#imagem-preview").css("display", "none");
  $("form[name='cadastroUsuarioCpd']")[0].reset();
  $("#idUsuarioCpd").val(0);
  $("#pass").addClass("form-required");
}

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
