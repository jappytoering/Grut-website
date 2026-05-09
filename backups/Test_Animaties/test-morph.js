const startD = "M 0 0 C 0 0, 50 0, 50 0 C 50 28, 72 50, 100 50 C 128 50, 150 28, 150 0 C 150 0, 200 0, 200 0 C 200 27.5, 188.8 52.5, 170.6 70.6 C 152.5 88.8, 127.5 100, 100 100 C 72.5 100, 47.5 88.8, 29.4 70.6 C 11.3 52.5, 0 27.5, 0 0 Z";
const textParts = startD.split(/-?\d+(?:\.\d+)?/g);
const startNums = startD.match(/-?\d+(\.\d+)?/g).map(Number);
let newD = textParts[0];
for (let i = 0; i < startNums.length; i++) {
    newD += startNums[i] + textParts[i+1];
}
console.log(newD === startD);
