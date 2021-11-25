(this.webpackJsonpleaderboard = this.webpackJsonpleaderboard || []).push([[0], {
    12: function(e, s, c) {},
    13: function(e, s, c) {},
    14: function(e, s, c) {},
    16: function(e, s, c) {
        "use strict";
        c.r(s);
        var t = c(1),
            n = c.n(t),
            i = c(7),
            l = c.n(i),
            r = (c(12), c(13), c(3)),
            a = c(4),
            o = c(6),
            d = c(5),
            j = (c(14), c(0)),
            u = function(e) {
                Object(o.a)(c, e);
                var s = Object(d.a)(c);
                function c() {
                    return Object(r.a)(this, c), s.apply(this, arguments)
                }
                return Object(a.a)(c, [{
                    key: "render",
                    value: function() {
                        return Object(j.jsxs)("tr", {
                            children: [Object(j.jsx)("th", {
                                scope: "row",
                                children: this.props.name
                            }), Object(j.jsx)("td", {
                                children: 1 + this.props.classement
                            }), Object(j.jsx)("td", {
                                children: this.props.score
                            }), Object(j.jsx)("td", {
                                children: 1 === this.props.hub ? "Hub de l'\xc9cole des Ponts" : "Hub distanciel (Discord)"
                            }), Object(j.jsx)("td", {
                                children: 1 === this.props.type_equipe ? "1A" : 2 === this.props.type_equipe ? "\xc9tudiante" : "Autre"
                            })]
                        })
                    }
                }]), c
            }(n.a.Component),
            b = function(e) {
                Object(o.a)(c, e);
                var s = Object(d.a)(c);
                function c(e) {
                    var t;
                    return Object(r.a)(this, c), (t = s.call(this, e)).state = {
                        scores: []
                    }, t
                }
                return Object(a.a)(c, [{
                    key: "componentDidMount",
                    value: function() {
                        var e = this;
                        this.socket = new WebSocket("wss://kiro.enpc.org/wss"),
                        this.socket.addEventListener("open", (function(e) {
                            console.log("Websocket connection is on!")
                        })),
                        this.socket.addEventListener("message", (function(s) {
                            console.log(s),
                            e.setState({
                                scores: JSON.parse(s.data)
                            })
                        }))
                    }
                }, {
                    key: "componentWillUnmount",
                    value: function() {
                        this.socket.close()
                    }
                }, {
                    key: "render",
                    value: function() {
                        return Object(j.jsx)("div", {
                            class:"table-responsive",
                            children: Object(j.jsx)("table", {
                                class:"box-tableau table table-hover text-white",
                                children: [Object(j.jsxs)("thead", {
                                    children: Object(j.jsxs)("tr", {
                                        class: "table-dark",
                                        children: [Object(j.jsx)("th", {
                                            scope:"col",
                                            children: "Nom d'\xe9quipe"
                                        }), Object(j.jsx)("th", {
                                            scope:"col",
                                            children: "Classement"
                                        }), Object(j.jsx)("th", {
                                            scope:"col",
                                            children: "Score"
                                        }), Object(j.jsx)("th", {
                                            scope:"col",
                                            children: "Hub"
                                        }), Object(j.jsx)("th", {
                                            scope:"col",
                                            children: "Type"
                                        })]
                                    })
                                }),Object(j.jsxs)("tbody", {
                                    children: this.state.scores.map((function(e, s) {
                                        return Object(j.jsx)(u, {
                                            name: e.nom,
                                            score: e.public_score,
                                            classement: s,
                                            hub: e.hub,
                                            type_equipe: e.type_equipe
                                        }, e.id)
                                    }))
                                })]
                            })
                        })
                    }
                }]), c
            }(n.a.Component);
        var h = function() {
            return Object(j.jsx)("div", {
                className: "App",
                children: Object(j.jsx)("header", {
                    className: "App-header",
                    children: Object(j.jsx)(b, {})
                })
            })
        };
        l.a.render(Object(j.jsx)(n.a.StrictMode, {
            children: Object(j.jsx)(h, {})
        }), document.getElementById("leaderboard"))
    }
}, [[16, 1, 2]]]);
//# sourceMappingURL=main.a424d41f.chunk.js.map