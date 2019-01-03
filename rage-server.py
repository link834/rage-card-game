#!/usr/bin/env python

# WebSockets server to synchronize states between Web Applications

import sys
import asyncio
import json
import logging
import websockets
import ssl
import pathlib
import random
import os


logging.basicConfig()
logging.getLogger().setLevel(logging.INFO)


if len(sys.argv) < 3:
    logging.error("\r\n\tUSAGE: " + sys.argv[0] + " <host or ip to bind to> <port>")
    sys.exit()

serverHost = sys.argv[1]
port = sys.argv[2]

__location__ = os.path.realpath(os.path.join(os.getcwd(), os.path.dirname(__file__)))

USERS = set()


##### NOTIFIERS #####

# General notify function for all events
async def notify_event(event):
    eventJson = json.loads(event)
    logging.info('relaying event: ' + eventJson['type'])
    await asyncio.wait([user.send(event) for user in USERS])

# General notify function for all server events
async def notify_server_event(event_type, event_data = -1):
    if USERS:
        event = json.dumps({'type': event_type, 'data': event_data})
        await notify_event(event)

# Notifies other users of new connections
async def notify_users():
    if USERS:       # asyncio.wait doesn't accept an empty list
        await notify_server_event('users', len(USERS))


##### FUNCTIONS #####

async def register(websocket):
    USERS.add(websocket)
    await notify_users()

async def unregister(websocket):
    USERS.remove(websocket)
    await notify_users()

async def serverFunction(websocket, path):
    # register(websocket) sends user_event() to websocket
    await register(websocket)
    try:

        # relay the command recieved from the Web Interface
        async for event in websocket:
            
            await notify_event(event)

            # check if the server should be stopped
            eventJson = json.loads(event)
            if eventJson['type'] == 'kill':
                logging.info('server stop')
                asyncio.get_event_loop().stop()


    finally:
        await unregister(websocket)


##### RUN SERVER #####

ssl_context = ssl.SSLContext(ssl.PROTOCOL_TLS_SERVER)
ssl_context.load_cert_chain(os.environ['WEB_STUFF'])
logging.info(os.environ['WEB_STUFF'])
start_server = websockets.serve(serverFunction, serverHost, port, ssl=ssl_context)

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
