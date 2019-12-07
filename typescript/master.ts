// to compile this file, go to terminal console and CD to typescript folder, then use watch mode: tsc -w

interface Person {
    first_name: string;
    last_name: string;
}

function logName(person: Person){
    console.log(person.first_name + " " + person.last_name)
}

let father = {first_name:"Josh", last_name:"Byvelds"};
logName(father);
