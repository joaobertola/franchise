function AddText(text){
if (document.mural.msg.createTextRange && document.mural.msg.caretPos){      
var caretPos = document.mural.msg.caretPos;      
caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ?
text + ' ' : text;
} 
else document.mural.msg.value += text;  
}
function smiley(){
AddTxt=" :)";
AddText(AddTxt);
}
function wink(){
AddTxt=" ;)";
AddText(AddTxt);
}
function cheesy(){
AddTxt=" :D";
AddText(AddTxt);
}
function grin(){
AddTxt=" ;D";
AddText(AddTxt);
}
function angry(){
AddTxt=" ~(";
AddText(AddTxt);
}
function sad(){
AddTxt=" :(";
AddText(AddTxt);
}
function shocked(){
AddTxt=" :o";
AddText(AddTxt);
}
function cool(){
AddTxt=" 8)";
AddText(AddTxt);
}
function huh(){
AddTxt=" ???";
AddText(AddTxt);
}
function rolleyes(){
AddTxt=" ::/";
AddText(AddTxt);
}
function tongue(){
AddTxt=" :P";
AddText(AddTxt);
}
function embarassed(){
AddTxt=" :-[";
AddText(AddTxt);
}
function lipsrsealed(){
AddTxt=" :-X";
AddText(AddTxt);
}
function undecided(){
AddTxt=" :-/";
AddText(AddTxt);
}
function kiss(){
AddTxt=" :-*";
AddText(AddTxt);
}
function cry(){
AddTxt=" '(";
AddText(AddTxt);
}
function coracao(){
AddTxt=" C>";
AddText(AddTxt);
}