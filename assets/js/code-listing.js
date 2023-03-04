(function () {
    /* global codeListing */
    /**
     * Replace the code tag with beautiful code block.
     *
     * @param codeElement
     */
    function beautifyElement(codeElement) {
        // get the lang of local block
        const localLang = codeElement.closest('[data-lang-active]').getAttribute('data-lang-active')
        // get the lang of current code box
        const currentLang = codeElement.getAttribute('data-lang')
        // create code block
        const mainElement = createElement({
            tag: 'pre',
            class: 'code-box' + (localLang === currentLang ? ' active' : ''),
            data: {lang: currentLang,}
        })
        const linesData = getLines(codeElement)
        // add lines indexes
        mainElement.append(createElement({class: 'code-box__lines', html: linesData[0],}))
        // add lines of code
        mainElement.append(createElement({class: 'code-box__code', html: linesData[1]}))
        // add copy button
        mainElement.append(createElement({
            tag: 'button',
            class: 'code-snippet__copy js-code-snippet-copy',
            html: codeListing.copy
        }))
        // add hidden code box element
        mainElement.append(createElement({tag: 'code', text: codeElement.innerHTML,}))
        // replace old code box with new code block
        codeElement.parentNode.replaceChild(mainElement, codeElement)
    }

    /**
     * Remove last empty elements from given array.
     *
     * @param arr
     * @returns {*}
     */
    function removeTrailingEmptyElements(arr) {
        while (arr.length > 0 && arr[arr.length - 1] === '') {
            arr.pop()
        }
        return arr
    }

    /**
     * Prepare indexes and code lines.
     *
     * @param element
     * @returns {(string|*)[]}
     */
    function getLines(element) {
        let start = false
        let codeLines = element.innerHTML
            .split("\n")
            // make strings containing only spaces empty
            // .map(item => '' !== item.trim() ? item : '')
            .map(item => {
                return item
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
            })
        // remove last empty elements
        codeLines = removeTrailingEmptyElements(codeLines)
        // remove first empty elements
        codeLines = codeLines
            .map(item => {
                if (item || start) {
                    start = true
                    return '<div>' + item + ' </div>'
                } else {
                    return ''
                }
            })
            .filter(item => item)
        const linesCount = codeLines.length
        let indexLines = []
        for (let i = 0; i < linesCount; i++) {
            indexLines.push('<div>' + (i + 1) + '</div>')
        }
        return [indexLines.join(''), codeLines.join('')]
    }

    /**
     * Beautify all code tags.
     */
    function prepareCodeBoxes() {
        document.querySelectorAll('code[data-lang]').forEach(codeElement => {
            beautifyElement(codeElement)
        })
    }

    /**
     * Set pointed tab and show pointed code box if it exists in local block.
     * Block - block with tabs and code boxes.
     */
    function setActiveTab() {
        // loop all blocks
        document.querySelectorAll('[data-lang-active]').forEach(blockElement => {
            // if current lang exist in local block
            if (blockElement.querySelector('[data-lang="' + lang + '"]')) {
                // loop tabs and codes
                blockElement.querySelectorAll('[data-lang]').forEach(element => {
                    // if global lang equal to current lang
                    if (lang === element.getAttribute('data-lang')) {
                        // set active
                        element.classList.add('active')
                    } else {
                        // set passive
                        element.classList.remove('active')
                    }
                })
            }
        })
    }

    /**
     * Put given text to clipboard.
     *
     * @param text
     */
    function unsecuredCopyToClipboard(text) {
        const textArea = document.createElement("textarea")
        textArea.value = text
        document.body.appendChild(textArea)
        // textArea.focus()
        textArea.select(0, 999999)
        try {
            document.execCommand('copy')
        } catch (err) {
            console.error('Unable to copy to clipboard', err)
        }
        document.body.removeChild(textArea)
    }

    /**
     * Add listeners for "copy" buttons.
     */
    function addCopyButtonListeners() {
        document.querySelectorAll('.js-code-snippet-copy').forEach(button => {
            button.addEventListener('click', event => {
                event.preventDefault()
                const element = event.target
                const parent = element.parentElement
                const code = parent.querySelector('.active code')
                // copy data to clipboard
                unsecuredCopyToClipboard(code.innerHTML.replaceAll('<br>', "\n"))
                element.innerHTML = codeListing.copied
                // set caption back in 2 seconds
                setTimeout(function () {
                    element.innerHTML = codeListing.copy
                }, 2000)
            })
        })
    }

    /**
     * Create an element from a given data.
     *
     * @param data
     * @returns {*}
     */
    function createElement(data) {
        const defaultData = {
            tag: 'div',
            class: '',
            data: {},
            text: '',
            html: '',
        }
        // merge data with defaults
        for (const key in defaultData) {
            if (!data[key] || '' === data[key] || [] === data[key]) {
                data[key] = defaultData[key]
            }
        }

        const element = document.createElement(data.tag)
        // get classes
        const classes = data.class.split(' ').filter(item => item)
        // if classes count more than 0
        if (classes.length) {
            element.classList.add(...classes)
        }
        for (const key in data.data) {
            // set data attributes
            element.setAttribute('data-' + key, data.data[key])
        }
        if (data.text) {
            // set content
            element.innerText = data.text
        }
        if (data.html) {
            // set content
            element.innerHTML = data.html
        }
        return element
    }

    /**
     * Rebuild pre blocks containing code boxes.
     */
    function prepareBlocks() {
        document.querySelectorAll('pre').forEach(preElement => {
            preElement.classList.add('code-snippet')
            if (codeListing['theme']) {
                preElement.classList.add(codeListing['theme'])
            }
            let tabs = []
            let activeFlag = false
            preElement.querySelectorAll('code').forEach((codeElement, index) => {
                if (!codeElement.getAttribute('data-lang')) {
                    codeElement.setAttribute('data-lang', 'listing_' + (index + 1))
                    codeElement.setAttribute('data-lang-caption', 'Listing ' + (index + 1))
                }
                activeFlag = lang === codeElement.getAttribute('data-lang')
                tabs.push({
                    lang: codeElement.getAttribute('data-lang'),
                    caption: codeElement.getAttribute('data-lang-caption') ? codeElement.getAttribute('data-lang-caption') : codeElement.getAttribute('data-lang'),
                    active: activeFlag,
                })
            })
            const tabsElement = createElement({class: 'code-snippet__tabs',})
            tabs.forEach((tab, index) => {
                const tabElement = createElement({
                    tag: 'button',
                    class: 'code-snippet__tab',
                    data: {lang: tab.lang,},
                    text: tab.caption,
                })
                if ((!activeFlag && index === 0) || tab.active) {
                    tabElement.classList.add('active')
                    // set local active lang
                    preElement.setAttribute('data-lang-active', tab.lang)
                }
                // add new tab element
                tabsElement.append(tabElement)
            })
            // add tabs element before content
            preElement.prepend(tabsElement)
            // rename tag
            preElement.outerHTML = preElement.outerHTML.replace(/pre/g, 'div')
        })
    }

    function addTabListeners() {
        document.querySelectorAll('[data-lang]').forEach(tab => {
            if ('code' !== tab.tagName.toLowerCase()) {
                tab.addEventListener('click', event => {
                    event.preventDefault()
                    lang = tab.getAttribute('data-lang')
                    setActiveTab()
                })
            }
        })
    }

    let lang = document.querySelector('[data-lang]').getAttribute('data-lang')
    prepareBlocks()
    prepareCodeBoxes()
    addTabListeners()
    addCopyButtonListeners()

})()