function preenche(dados,anoletivo,escola,ano,turma,disciplina)
				{
					$.get('FiltrosAvalFinaisGET.php',{act:dados,anoletivo:anoletivo, escola:escola, ano:ano, turma:turma, disciplina:disciplina}).done(function(data) 
					{	
						var obj = JSON.parse(data);	
						
						if(obj)
						{						
							var valor = $('#'+dados).val();
						
							$('#'+dados).empty().append($('<option />').text(dados).attr('disabled','disabled').attr('selected','selected'));									
							
							for(var i=0; i<obj.length; i++)
							$('#'+dados).append($('<option />').val(obj[i][0]).text(obj[i][1]));							
							
							if (valor)
							{								
								$('#'+dados).val(valor);						
							}					
							
							$('#'+dados).change();				
						}						
					})
				}