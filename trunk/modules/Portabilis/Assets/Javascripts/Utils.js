
// jquery utils

function buildId(id) {
  return typeof(id) == 'string' && id.length > 0 && id[0] != '#' ? '#' + id : id;
}

function safeLog(value) {
  if(typeof(console) != 'undefined' && typeof(console.log) == 'function')
    console.log(value);
}


// string utils

function safeToUpperCase(value) {
  if (typeof(value) == 'string')
    value = value.toUpperCase();

  return value;
}


function safeToLowerCase(value) {
  if (typeof(value) == 'string')
    value = value.toLowerCase();

  return value;
}


function safeCapitalize(value) {
  if (typeof(value) == 'string') {
    value        = value.toLowerCase();
    var words    = value.split(' ');

    $j.each(words, function(index, word){
      words[index] = word.substr(0, 1).toUpperCase() + word.substr(1);
    });

    value = words.join(' ');
  }

  return value;
}


function safeSort(values) {
  try{
    var sortedValues = values.sort(function(a, b) {
      if (typeof(a) == 'string' && typeof(b) == 'string')
        var isGreaterThan = a.toLowerCase() > b.toLowerCase();
      else
        var isGreaterThan = a > b;

     return isGreaterThan ? 1 : -1;
    });
    return sortedValues;
  }
  catch(e) {
    safeLog('Erro ao ordenar valores: ' + e);
    safeLog(values);
    return values;
  }
}


function safeUtf8Decode(s) {
  try {
    s = decodeURIComponent(escape(s));
  }
  catch(e) {
    safeLog('Erro ao decodificar string utf8: ' + s);
  }

  return s;
}