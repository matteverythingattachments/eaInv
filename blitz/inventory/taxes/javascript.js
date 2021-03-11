jQuery(document).ready(function($){
// Create our number formatted ------------------------------------------
var formatter = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 2, });
  
	$(document).on('click', '.statustab', function(e){ 
    var getText = $(this).text();
    var taxtotal = 0;
    var paymenttotal = 0;
    var totalorders = 0;
    
    if($(this).hasClass("hidestatus")){
      $(this).removeClass("hidestatus");
      $( "."+getText ).removeClass("hide");
    }else{
      $(this).addClass("hidestatus");
      $( "."+getText ).addClass("hide");
    }
    $('.orders:not(.hide)').each(function( index ) {
        taxtotal += parseFloat($(this).attr("data-tax"));
        paymenttotal += parseFloat($(this).attr("data-payment"));
        totalorders ++;
    });
    $('#taxtotal').text(formatter.format(taxtotal));
    $('#paymenttotal').text(formatter.format(paymenttotal));
    $('#orderstotal').text("Orders: "+totalorders);
	});
  
  
  $(document).on('click', '.statustab2', function(e){ 
    var getText = $(this).text();
    if($(this).hasClass("hidestatus")){
      $(this).removeClass("hidestatus");
      $( "."+getText ).removeClass("hide");
    }else{
      $(this).addClass("hidestatus");
      $( "."+getText ).addClass("hide");
    }
    
    calculatetable();
	});	
 

  $(document).on('blur', '.inputvar', function(e){ 
    calculatetable();
  });
  
  
  $(document).on('click', '.orderid', function(e){ 
    var getid = $(this).attr("rel");
    $("#tr_"+getid).addClass("hide");
    calculatetable();
  });
  
  $(document).on('click', '.print', function(e){ 
    window.print();
  });
  
  $(document).on('click', '#showall', function(e){ 
     $(".hide").removeClass("hide");
     calculatetable();
  });

  $(document).on('click', '.nonexempt', function(e){ 
     var getid = $(this).attr("rel");
     $('#totalrecipts_'+getid).text($('#totalrecipts_'+getid).attr('data-price'));
     $('#totalexempt_'+getid).text("");
     $('#totalstatetax_'+getid).text($('#totalstatetax_'+getid).attr('data-price'));
     $('#totalcountytax_'+getid).text($('#totalcountytax_'+getid).attr('data-price'));
     $('#totalsalestax_'+getid).text($('#totalsalestax_'+getid).attr('data-price'));
     $('#totaldifferenttax_'+getid).val($('#totalsalestax_'+getid).attr('data-price'));
     calculatetable();
  });  
  
  $(document).on('click', '.exempt', function(e){ 
     var getid = $(this).attr("rel");
     var gettotal = $('#totalrecipts_'+getid).text();
     $('#totalrecipts_'+getid).text("");
     $('#totalexempt_'+getid).text(gettotal);
     $('#totalstatetax_'+getid).text('$0.00');
     $('#totalcountytax_'+getid).text('$0.00');
     $('#totalsalestax_'+getid).text('$0.00');
     $('#totaldifferenttax_'+getid).val('$0.00');
     calculatetable();
  });
  
  function calculatetable() {
    var taxtotal = 0;
    var paymenttotal = 0;
    var totalorders = 0;
    var totalstatetax = 0;
    var totalcountytax = 0;
    var totaldifferenttax = 0;
    var totalexempt = 0;
    var getid = 0;
    
    $('.orders:not(.hide)').each(function( index ) {
        getid = $(this).attr("rel");
        taxtotal += parseFloat($("#totalsalestax_"+getid).text().replace(/[^\d.-]/g, ''));
        totalstatetax += parseFloat($("#totalstatetax_"+getid).text().replace(/[^\d.-]/g, ''));
        totalcountytax += parseFloat($("#totalcountytax_"+getid).text().replace(/[^\d.-]/g, ''));
        totaldifferenttax += parseFloat($("#totaldifferenttax_"+getid).val().replace(/[^\d.-]/g, ''));
        if($("#totalrecipts_"+getid).text().replace(/[^\d.-]/g, '') !== ""){
          paymenttotal += parseFloat($("#totalrecipts_"+getid).text().replace(/[^\d.-]/g, ''));
        }
        if($("#totalexempt_"+getid).val().replace(/[^\d.-]/g, '') !== ""){
          totalexempt += parseFloat($("#totalexempt_"+getid).val().replace(/[^\d.-]/g, '')); 
        }
        totalorders ++;
    });
    $('#taxtotal').text(formatter.format(taxtotal));
    $('#totalsalestax').text(formatter.format(taxtotal));
    $('#paymenttotal').text(formatter.format(paymenttotal));
    $('#totalrecipts').text(formatter.format(paymenttotal));
    $('#totalstatetax').text(formatter.format(totalstatetax));
    $('#statetaxtotal').text(formatter.format(totalstatetax));
    $('#totalcountytax').text(formatter.format(totalcountytax));
    $('#countytaxtotal').text(formatter.format(totalcountytax));
    $('#totaldifferenttax').text(formatter.format(totaldifferenttax));
    $('#taxtotaldifferent').text(formatter.format(totaldifferenttax));
    $('#exempttotal').text(formatter.format(totalexempt));
    $('#totalexempt').text(formatter.format(totalexempt));
    
    $('#orderstotal').text(totalorders);
    
    var counthide = $('.hide').length;
    $('#hidecount').text(counthide);
    
  }
  
  
}); // end Document Ready	