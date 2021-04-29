import React from "react";
import './style.css';

// TODO : ajouter le css
// TODO : ajouter le hub
// TODO : ajouter si la derniere instance etait valide

// Fichier de config ? Modification à la main ? Variable d'environnement ?
// Tout cela ne me plait guère...
const WEBSOCKET_URL = "wss://kiro.enpc.org/wss"

class Score extends React.Component {
    render() {
        return (
            <div class="row2">
                <div class="cell">{this.props.name}</div>
                <div class="cell">{1+this.props.classement}</div>
                <div class="cell">{this.props.score}</div>
                <div class="cell">{(this.props.hub === 1) ? "Hub de l'École des Ponts" : "Hub distanciel (Discord)"}</div>
                <div class="cell">{(this.props.type_equipe === 1) ? "1A" : (this.props.type_equipe === 2) ? "Étudiante" : "Autre"}</div>
            </div>
        );
    }
}


export class ScoreBoard extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            scores: []
        }
    }

    componentDidMount() {
        // Create WebSocket connection.
        this.socket = new WebSocket(WEBSOCKET_URL);

        // Connection opened
        this.socket.addEventListener('open', function (event) {
            console.log('Websocket connection is on!');
        });

        // Listen for messages
        this.socket.addEventListener('message', (event) => {
            console.log(event);
            this.setState({
                scores: JSON.parse(event.data)
            })
        });
    }

    componentWillUnmount() {
        this.socket.close();
    }


    render() {
        return (
            <div class="container">
                <div class="wrap-table100" style={{marginTop: '5vh'}}>
                    <div class="table">
                        <div class="row2 header">
                            <div class="cell">Nom d'équipe</div>
                            <div class="cell">Classement</div>
                            <div class="cell">Score</div>
                            <div class="cell">Hub</div>
                            <div class="cell">Type</div>
                        </div>
                            {
                                this.state.scores.map((element, index) => {
                                    return <Score name={element.nom} score={element.public_score} classement={index} hub={element.hub} type_equipe={element.type_equipe} key={element.id} />
                                })
                            }
                    </div>
                </div>
            </div>
        );
    }
}