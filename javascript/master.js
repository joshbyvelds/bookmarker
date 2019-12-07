// to compile this file, go to terminal console and CD to typescript folder, then use watch mode: tsc -w
function logName(person) {
    console.log(person.first_name + " " + person.last_name);
}
var father = { first_name: "Josh", last_name: "Byvelds" };
logName(father);
