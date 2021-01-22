function multiplierQuantite(nb){
  let portion = $('div.recette > p > strong').eq(1).html();
  let portions = parseFloat(nb*portion).toFixed(1);
  $('div.recette > p > strong').eq(0).html(portions);
  $('.p1 li').each(function(index){
     $('.recette li strong').eq(index).html(nb * $(this).html());
    console.log($(this).html());
  })
}

$('#nb-personnes').change(function(e){multiplierQuantite(e.target.value);})
$('#remove-recette').click(()=> {
    if(confirm("Appuyez sur OK pour confirmer la suppression de la recette")){
      console.log("removerecette");
      let url=window.location.href+'/remove-recette';
      async function delRecette(){
        await $.post(url, null, function (data, status) {
        window.location.href='../recettes';
        });
      };
      delRecette();
    }
});

function getRecettes(n, c){
  if (n.trim() == '') {
    n = 'null';
  }
  $.ajax({
    url : 'index.php/recettes/' + n.trim() + '/' + c,
    success : function(data){
      $('.recettes-liste').html(data);
    }
  })
}



$('#recherche-recette').keyup(function(e){
  getRecettes(e.target.value, $('.type-recette').children("option:selected").html());
})

$('.type-recette > option').click(function(e){
  getRecettes($('#recherche-recette').val(), e.target.innerHTML);
})
