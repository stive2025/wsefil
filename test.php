<?php

// $data="/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEABsbGxscGx4hIR4qLSgtKj04MzM4PV1CR0JHQl2NWGdYWGdYjX2Xe3N7l33gsJycsOD/2c7Z//////////////8BGxsbGxwbHiEhHiotKC0qPTgzMzg9XUJHQkdCXY1YZ1hYZ1iNfZd7c3uXfeCwnJyw4P/Zztn////////////////CABEIACkASAMBIgACEQEDEQH/xAAuAAACAwEBAAAAAAAAAAAAAAACAwAEBQEGAQEBAQAAAAAAAAAAAAAAAAAAAQL/2gAMAwEAAhADEAAAALoPGV6DcYKNPKzoaRhvEklnswsogOdqzSsazU1lfCE5JD39W1VROJuYS1lsEWNyvNKnYn//xAAgEAADAAICAwADAAAAAAAAAAAAAQIDERAxBBIhEzJB/9oACAEBAAE/ANFx7GOJyzq+0eR4mobkrG4+tCtqtoz57puelzria9T821qjzIpr50PULbLfs9jTXDka5y+ZpOC3tsY3xcjQzPmWNaXZmqaSpLT/AKMfNlFdMzNumN8NPmyiumZf2Yyexl98f//EABcRAAMBAAAAAAAAAAAAAAAAAAEQESD/2gAIAQIBAT8AyVMBVf/EABgRAAMBAQAAAAAAAAAAAAAAAAEQIAAR/9oACAEDAQE/AJEjFcX/2Q==";
// file_put_contents('./app/public/image.png', $data);

if(!is_dir('public/files/const')){
    echo "NO EXISTE";
    mkdir('public/files/const');
}else{
    echo "EXISTE";
}