<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP | MySQL | React.js | Axios Example</title>
    <script src="https://unpkg.com/react@16/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body>
    <div id='root'></div>

    <script type="text/babel">
        class ContactForm extends React.Component {
            state = {
                name: '',
                email: '',
                country: '',
                city: '',
                job: '',
            }

            handleFormSubmit(event) {
                event.preventDefault();

                let formData = new FormData();
                formData.append('name', this.state.name);
                formData.append('email', this.state.email);
                formData.append('country', this.state.country);
                formData.append('city', this.state.city);
                formData.append('job', this.state.job);

                axios.post('/api/contacts.php', formData)
                    .then((response) => {
                        alert("Contacto agregado correctamente!");
                        this.setState({ name: '', email: '', country: '', city: '', job: '' });
                        this.props.onContactAdded();
                    })
                    .catch((error) => {
                        alert("Error al agregar contacto.");
                    });
            }

            render() {
                return (
                    <form>
                        <label>Name</label>
                        <input type="text" name="name" value={this.state.name}
                            onChange={e => this.setState({ name: e.target.value })} required />

                        <label>Email</label>
                        <input type="email" name="email" value={this.state.email}
                            onChange={e => this.setState({ email: e.target.value })} required />

                        <label>Country</label>
                        <input type="text" name="country" value={this.state.country}
                            onChange={e => this.setState({ country: e.target.value })} required />

                        <label>City</label>
                        <input type="text" name="city" value={this.state.city}
                            onChange={e => this.setState({ city: e.target.value })} required />

                        <label>Job</label>
                        <input type="text" name="job" value={this.state.job}
                            onChange={e => this.setState({ job: e.target.value })} required />

                        <input type="submit" onClick={e => this.handleFormSubmit(e)} value="Create Contact" />
                    </form>
                );
            }
        }

        class App extends React.Component {
            state = { contacts: [] }

            componentDidMount() {
                this.fetchContacts();
            }

            fetchContacts = () => {
                axios.get('/api/contacts.php')
                    .then(response => {
                        this.setState({ contacts: response.data });
                    })
                    .catch(error => console.log(error));
            }

            render() {
                return (
                    <React.Fragment>
                        <h1>Contact Management</h1>
                        <table border='1' width='100%'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>Job</th>
                                </tr>
                            </thead>
                            <tbody>
                                {this.state.contacts.map((contact) => (
                                    <tr key={contact.id}>
                                        <td>{contact.name}</td>
                                        <td>{contact.email}</td>
                                        <td>{contact.country}</td>
                                        <td>{contact.city}</td>
                                        <td>{contact.job}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                        <h2>Add New Contact</h2>
                        <ContactForm onContactAdded={this.fetchContacts} />
                    </React.Fragment>
                );
            }
        }

        ReactDOM.render(<App />, document.getElementById('root'));
    </script>
</body>
</html>
