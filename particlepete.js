

var particle = new Particle();
particle.login({username: 'phorrack@casaria.net', password: 'adv57130'});


function LEDFunction(p) {
var fnPr = particle.callFunction({ deviceId: '200032000b51343334363138', name: 'RlyPuLse', argument: p, auth: 'efda0f38d6284abaf3735a6cc7350b41848d3aad' });
fnPr.then(
  function(data) {
    console.log('Function called succesfully:', data);
  }, function(err) {
    console.log('An error occurred:', err);
  });	
  return;              // 
}