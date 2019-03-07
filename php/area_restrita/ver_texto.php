<td class="frm_inputi_2" colspan="8">

          <i>Digite o conteúdo da página</i> <br />
            <?php

                    include "fckeditor/fckeditor.php"; //Chama a classe fckeditor

                    $editor = new FCKeditor("conteudoPag");//Nomeia a area de texto
                    $editor-> BasePath = "fckeditor/"; //Informa a pasta do FKC Editor

                    $editor-> Value = $pag_texto;//Informa o valor inicial do campo, no exemplo está vazio
                    $editor-> Width = "1103"; //informa a largura do editor
                    $editor-> Height = "100";//informa a altura do editor
                    $editor-> Create();// Cria o editor
            ?>
</td>