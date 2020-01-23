function findMax(arr) {
  let max = arr[0];
  arr.forEach(v => {
    if (v > max) max = v;
  });

  return max;
}

function findMin(arr) {
  let min = arr[0];
  arr.forEach(v => {
    if (v < min) min = v;
  });
  return min;
}

function isFactor(arr, val) {
  for (const item of arr) {
    if (item % val !== 0) return false;
  }
  return true;
}

function isMultiple(arr, val) {
  for (const item of arr) {
    if (val % item !== 0) return false;
  }
  return true;
}

function getTotalX(a, b) {
  // Write your code here
  const solutionSet = [];

  const maxInt = findMin(b);
  const minInt = findMax(a);

  console.log(minInt);
  console.log(maxInt);

  for (let i = minInt; i <= maxInt; i++) {
    if (isMultiple(a, i) && isFactor(b, i)) solutionSet.push(i);
  }

  return solutionSet;
}

console.log(getTotalX([2, 4], [16, 32, 96]));
