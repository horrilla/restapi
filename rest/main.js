let id = null;

async function getNotes() {
    let res = await fetch('http://api.rest/api/v1/notebook');
    let data = await res.json();


    const pagination = document.querySelector('#pagination');
    const postList = document.querySelector('.post-list');
    let itemsLi = [];
    const notesOnPage = 6;
    let countPaginations = Math.ceil(data.length / notesOnPage);

    document.querySelector('#pagination').innerHTML = ``;
    for (let i = 1; i <= countPaginations; i++) {
        let li = document.createElement('li');
        li.innerHTML = i;
        pagination.appendChild(li);
        itemsLi.push(li);
    }
    let active;
    let first = true;

    if (first) {
        if (active) {
            active.classList.remove('active');
        }
        active = itemsLi[0];
        itemsLi[0].classList.add('active');

        let pageNum = +itemsLi[0].innerHTML;
        let start = (pageNum - 1) * notesOnPage;
        let end = start + notesOnPage;

        let notesPage = data.slice(start, end);

        document.querySelector('.post-list').innerHTML = ``;
        notesPage.forEach((note) => {
            document.querySelector('.post-list').innerHTML += `
<div class="col-4">
    <div class="card">
       <div class="card-body">
            <p class="card-title"><b>ФИО:</b> ${note.name}</p>
            <p class="card-text"><b>Компания:</b> ${note.company}</p>
            <p class="card-text"><b>Телефон:</b> ${note.phone}</p>
            <p class="card-text"><b>Почта:</b> ${note.email}</p>
            <p class="card-text"><b>Дата рождения:</b> ${note.born}</p>
            <p><b>Фото:</b></p>
            <div class="img">
                <img src="${note.photo}"></img>   
            </div>  
            <a class="btn btn-primary" onclick="deleteNote(${note.id})" id="delete">Удалить</a>
            <a class="btn btn-primary" onclick="selectNote('${note.id}', '${note.name}', '${note.company}', '${note.phone}', '${note.email}', '${note.born}')" id="delete">Редактировать</a>
        </div>
    </div>
</div>`;
    });
}


    for (let item of itemsLi) {
        item.addEventListener('click', () => {

            if (active) {
                active.classList.remove('active');
            }
            active = item;
            item.classList.add('active');

            let pageNum = +item.innerHTML;
            let start = (pageNum - 1) * notesOnPage;
            let end = start + notesOnPage;

            let notesPage = data.slice(start, end);


            document.querySelector('.post-list').innerHTML = ``;
            notesPage.forEach((note) => {
                document.querySelector('.post-list').innerHTML += `
    <div class="col-4">
        <div class="card">
           <div class="card-body">
                <p class="card-title"><b>ФИО:</b> ${note.name}</p>
                <p class="card-text"><b>Компания:</b> ${note.company}</p>
                <p class="card-text"><b>Телефон:</b> ${note.phone}</p>
                <p class="card-text"><b>Почта:</b> ${note.email}</p>
                <p class="card-text"><b>Дата рождения:</b> ${note.born}</p>
                <p><b>Фото:</b></p>
                <div class="img">
                    <img src="${note.photo}"></img>   
                </div>  
                <a class="btn btn-primary" onclick="deleteNote(${note.id})" id="delete">Удалить</a>
                <a class="btn btn-primary" onclick="selectNote('${note.id}', '${note.name}', '${note.company}', '${note.phone}', '${note.email}', '${note.born}')" id="delete">Редактировать</a>
            </div>
        </div>
    </div>`;
            });

        })
    }

}


async function addNote() {

    let formData = new FormData(document.querySelector('#form-add'));


    let res = await fetch('http://api.rest/api/v1/notebook', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();


    if (data.status === true) {
        await getNotes();
    }
}

const addnote = document.querySelector('#add-note');
addnote.addEventListener('click', (e) => {
    e.preventDefault();
    addNote();
    const form = document.querySelector('#form-add');
    form.reset();
});

async function deleteNote(id) {
    const res = await fetch(`http://api.rest/api/v1/notebook/${id}`, {
        method: 'DELETE'
    });
    const data = await res.json();

    if (data.status === true) {
        await getNotes();
    }
}

function selectNote(idNote, name, company, phone, email, born) {
    id = idNote;
    document.querySelector('#edit-name').value = name;
    document.querySelector('#edit-company').value = company;
    document.querySelector('#edit-phone').value = phone;
    document.querySelector('#edit-email').value = email;
    document.querySelector('#edit-born').value = born;

}

async function updateNote() {

    const formData = new FormData(document.querySelector('#edit-form'));

    const res = await fetch(`http://api.rest/api/v1/notebook/${id}`, {
        method: 'POST',
        body: formData
    });

    const data = await res.json();

    if (data.status === true) {
        await getNotes();
    } else {
        console.error(data.status);
    }
}

const formUpdate = document.querySelector('#edit-note');
formUpdate.addEventListener('click', (e) => {
    e.preventDefault();
    updateNote();
});


const cancel = document.querySelector('#cancel-post');
cancel.addEventListener('click', (e) => {
    e.preventDefault();
    const form = document.querySelector('#edit-form');
    form.reset();
});


getNotes();




