'use strict';

function getWords(str) {
  return str.split(/\s+/);
}

function splitWord(str, step) {
  var res = '';
  var i = 0, counter = 0;
  while (i < str.length) {
    res += str[i];
    counter++;
    if (counter === step) {
      res += ' ';
      counter === 0;
    }

    i++;
  }
  return res;
}

module.exports = {
  labelify: function(str, step) {
    str = str.trim();
    if (str.length < step) {
      return str;
    }

    var words = getWords(str);

    var res = '';
    var i = 0;
    var first = true;

    for (i = 0; i < words.length; ++i) {
      if (words[i].length > step) {
        words[i] = splitWord(words[i], step);
      }
      if (first) {
        first = false;
      } else {
        res += ' ';
      }
      res += words[i];
    }

    return res;
  },
  parseLabel: function(percentage, text, decimals) {
    if (!decimals) {
      decimals = 1;
    }
    var result = '';
    result += parseFloat(percentage).toFixed(decimals);
    result += '% - ' + text;

    return result;
  }
};
