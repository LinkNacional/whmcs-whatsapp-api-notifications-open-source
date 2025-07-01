const submitBtn = document.querySelector('form[action="/index.php?rp=/password/reset"] button[type="submit"]')

submitBtn.addEventListener('click', (e) => {
    e.preventDefault()
    console.log('checking...')
})
