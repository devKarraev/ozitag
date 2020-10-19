import React, {useEffect, useState} from "react"
import "./SingleView.css"

export const SingleView = (props) => {

    const [postData, setPostData] = useState(null)
    const [errorMessage, setErrorMessage] = useState([])
    const [successMessage, setSuccessMessage] = useState(null)

    useEffect(() => {
        const postId = props.match.params.flatId
        async function fetchPostData() {
            try {
                const response = await fetch(`/api/posts/${postId}`)
                let data = await response.json()
                data.main_content = data.main_content.replace(/(<([^>]+)>)/gi, "")
                setPostData(data)
            } catch (e) {

            }
        }
        fetchPostData()
    }, [])

    const textAreaHandler = e => {
        setPostData({...postData, [e.target.name]: e.target.value})
    }

    const updateHandler = async () => {
        try {
            const response = await fetch(`/api/posts/update/${postData.id}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    mainContent: postData.main_content,
                    title: postData.title,
                    price: postData.price
                })
            })
            const data = await response.json()
            if (!response.ok) {
                let messages = []
                for (let message in data.errors) {
                    messages.push(data.errors[message][0])
                }
                setErrorMessage(messages)
            } else {
                setSuccessMessage(data)
                setTimeout(() => {
                    setSuccessMessage(null)
                }, 2000)
            }
        } catch (e) {
            console.log(e)
        }
    }

    return (
        <div className="text-area-block">
            { successMessage &&
                <p style={{color: 'green', marginBottom: '3px'}}>{successMessage}</p>
            }
            {errorMessage.map((message, idx) => (
                <p
                    key={idx}
                    style={{color: 'red', marginBottom: '3px'}}
                >
                    {message}
                </p>
            ))}
            {postData != null &&
                <div>
                    <div className="form-group shadow-textarea">
                        <label htmlFor="title">Заголовок</label>
                        <input
                            onChange={e => textAreaHandler(e)}
                            defaultValue={postData.title}
                            id="title"
                            type="text"
                            name="title"
                            className="form-control"
                        />

                        <label htmlFor="price">Цена</label>
                        <input
                            onChange={e => textAreaHandler(e)}
                            defaultValue={postData.price}
                            id="price"
                            type="text"
                            name="price"
                            className="form-control"
                        />

                        <label htmlFor="mainContent">Основной текст</label>
                        <textarea
                            onChange={e => textAreaHandler(e)}
                            className="form-control z-depth-1"
                            name="main_content"
                            id="mainContent"
                            defaultValue={postData.main_content}
                        />

                        <button
                            onClick={updateHandler}
                            type="button"
                            className="btn btn-success mt-2"
                        >
                            Сохранить
                        </button>
                    </div>
                </div>
            }
        </div>
    )
}
