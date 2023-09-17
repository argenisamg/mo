let currentPage = 1;
let globalPagination = 0;
showPage(currentPage);

function openPage(pageName) {

    const tabcontent = document.getElementsByClassName('tabcontent');  
    for (let i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = 'none';        
    }

  const tablinks = document.getElementsByClassName('tablink');
  for (let i = 0; i < tablinks.length; i++) {
    tablinks[i].classList.remove('active');
  }

  let currentPageBro = document.getElementById(pageName);
  currentPageBro.style.display = 'block';  
  event.currentTarget.classList.add('active');    
  globalPagination = parseInt(currentPageBro);
  
} // end openPage

function changePage(n) {    
    showPage(currentPage + n);
} // end changePage

function showPage(page) {
  const tabcontent = document.getElementsByClassName('tabcontent');
  if (page < 1) {
    currentPage = 1;
  } else if (page > tabcontent.length) {
    currentPage = tabcontent.length;
  } else {
    currentPage = page;
  }

  for (let i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = 'none';
  }

  const tablinks = document.getElementsByClassName('tablink');
  for (let i = 0; i < tablinks.length; i++) {
    tablinks[i].classList.remove('active');
  }

  document.getElementById('Page' + currentPage).style.display = 'block';
  tablinks[currentPage - 1].classList.add('active');
} //end showPage
