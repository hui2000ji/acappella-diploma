"use strict"

let ctx = null
let canvas = document.getElementById('canvas')
let lastUploadedImgFile = ''
let imgLoaded = false

let initCtx = function(clear = true) {
    ctx = canvas.getContext('2d')
    if (clear)
        ctx.clearRect(0, 0, canvas.width, canvas.height)
}

let showCanvas = function (canvas) {
    canvas.toBlob(function(blob) {
        let targetImg = document.getElementById('final-image')
        if (targetImg.src)
            URL.revokeObjectURL(targetImg.src)
        targetImg.src = URL.createObjectURL(blob)
    })
}

let drawImageThenShowCanvas = function(img, ctx) {
    ctx.drawImage(img, 2885, 1060, 569, 758)
    showCanvas(ctx.canvas)
}

let croppie = new Croppie(document.getElementById('cropper'), {
    viewport: { width: '240px', height: '320px' },
    showZoomer: false,
    enableOrientation: true
});

let cropImg = function() {
    croppie.result('blob').then(function(blob) {
        let img = document.getElementById('cropped-photo');
        if (img.src)
            URL.revokeObjectURL(img.src)
        img.src = URL.createObjectURL(blob)
        img.onload = () => {
            drawImageThenShowCanvas(img, ctx)
            imgLoaded = true
        }
    });
}

let drawCanvas = function() {
    let progress = 0
    
    initCtx()

    let backgroundImg = document.getElementById('background-img')
    ctx.drawImage(backgroundImg, 0, 0, 3780, 2598)

    let nameInput = document.getElementById('input-name-button')
    if (nameInput.value) {
        progress += 25
        ctx.font = '76px STSong, Noto Serif SC';
        ctx.textAlign = 'center'
        ctx.fillText(nameInput.value, 760, 1270)
    }

    let birthInput = document.getElementById('input-birth-button')
    if (birthInput.value) {
        progress += 25
        let birth = new Date(birthInput.value)
        ctx.font = '76px STSong, Noto Serif SC';
        ctx.textAlign = 'center'
        ctx.fillText("" + birth.getFullYear(), 1145, 1270)
        ctx.fillText("" + (birth.getMonth() + 1), 1428, 1270)
        ctx.fillText("" + birth.getDate(), 1685, 1270)
    }
    
    let yearInput = document.getElementById('input-year-button')
    if (yearInput.value && yearInput.value <= 2019 && yearInput.value >= 2010) {
        progress += 25
        let signature = document.getElementById('signature-' + yearInput.value)
        ctx.drawImage(signature, 1700, 1920, 525, 393)
        ctx.font = '76px STSong, Noto Serif SC';
        ctx.textAlign = 'center'
        let roman2sc = {
            "0": "〇",
            "1": "一",
            "2": "二",
            "3": "三",
            "4": "四",
            "5": "五",
            "6": "六",
            "7": "七",
            "8": "八",
            "9": "九"
        }
        ctx.fillText(yearInput.value.split("").map(roman => roman2sc[roman]).join(''), 2730, 2265)
        ctx.fillText("七", 3085, 2265)
        ctx.fillText("一", 3305, 2265)
    }

    let photoInput = document.getElementById('input-photo-button')
    if (photoInput.value) {
        progress += 25
        let img = document.getElementById('photo')

        if (lastUploadedImgFile !== photoInput.files[0]) {
            imgLoaded = false
            lastUploadedImgFile = photoInput.files[0]
            if (img.src)
                URL.revokeObjectURL(img.src)
            img.src = URL.createObjectURL(photoInput.files[0]);
    
            img.onload =  function() {
                let cropElem = document.getElementById('cropper-display')
                let imgScale = img.width / img.height
                if (imgScale < 0.72 || imgScale > 0.78) {
                    cropElem.style.display = ''
                    croppie.bind({
                        url: img.src
                    }).then(() => cropImg())
                } else {
                    cropElem.style.display = 'none'
                    drawImageThenShowCanvas(img, ctx)
                    imgLoaded = true
                }
            }
        } else if (imgLoaded) {
            let imgScale = img.width / img.height
            if (imgScale < 0.72 || imgScale > 0.78) {
                let croppedImg = document.getElementById('cropped-photo')
                drawImageThenShowCanvas(croppedImg, ctx)
            } else drawImageThenShowCanvas(img, ctx)
        }
    }

    if (!photoInput.value)
        showCanvas(canvas)
    let progressBarElem = document.getElementById('progress-bar')
    progressBarElem.style.width = progress + '%'
}

let rotateImg = function(deg) {
    croppie.rotate(deg)
}

window.onload = () => drawCanvas()